<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Invoice::with(['company_name', 'customer_name'])->orderBy('id', 'desc');
            return DataTables::of($query)
                ->addColumn('company', function ($invoice) {
                    return $invoice->company_name ? $invoice->company_name->name : '-';
                })
                ->addColumn('customer', function ($invoice) {
                    return $invoice->customer_name ? $invoice->customer_name->name : '-';
                })
                ->addColumn('actions', function ($invoice) {
                    if($invoice->type == 1)
                    {
                        $editUrl = route('invoice.edit', $invoice->id);
                        $downloadUrl = route('invoice.pdf', Crypt::encrypt($invoice->id)) ;
                    }else{
                        $editUrl = route('non.gst.invoice.edit', $invoice->id);
                        $downloadUrl = route('non.gst.invoice.pdf', Crypt::encrypt($invoice->id)) ;
                    }
                    $deleteUrl = route('invoice.destroy', $invoice->id);
                    $buttons = '
                        <a href="' . $downloadUrl . '" target="_blank"
                            class="btn btn-sm btn-warning me-2">
                            <i class="fa fa-download me-2"></i> Download
                        </a>
                        <a href="' . $editUrl . '" class="btn btn-sm btn-primary me-2">
                            <i class="fa fa-edit me-2"></i> Edit
                        </a>
                        <a href="' . $deleteUrl . '" class="btn btn-sm btn-danger delete-btn me-2">
                            <i class="fa fa-trash me-2"></i> Delete
                        </a>
                    ';
                    return $buttons;
                })
                ->rawColumns(['company','customer','actions'])
                ->make(true);
        }
        return view('invoice.index');
    }

    public function create()
    {
        $companies = Company::where('created_by', Auth::user()->id)->get();
        $customers = Customer::where('created_by', Auth::user()->id)->get();
        $generatedInvoiceNumber = $this->generateInvoiceNumber();
        return view('invoice.create', compact('companies', 'customers', 'generatedInvoiceNumber'));
    }

   

    private function generateInvoiceNumber()
    {
        $lastInvoice = Invoice::orderBy('invoice_number', 'desc')->first();

        if ($lastInvoice && preg_match('/PI(\d+)/', $lastInvoice->invoice_number, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return 'PI' . $nextNumber;
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer'     => 'required',
            'company'      => 'required',
            'invoice_date' => 'required',
            'due_date'     => 'required',
            'currency'     => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $now = \Carbon\Carbon::now();

        // Use provided invoice number or generate new one
        // $invoiceNumber = $request->invoice_number ?: $this->generateInvoiceNumber();

        // Financial Year calculation
        $yearStart = $now->month >= 4 ? $now->year : $now->year - 1;
        $yearEnd = $yearStart + 1;
        $financialYearStart = \Carbon\Carbon::create($yearStart, 4, 1); // April 1st
        $financialYearEnd   = \Carbon\Carbon::create($yearEnd, 3, 31, 23, 59, 59); // March 31st

        
        $maxNumber = Invoice::whereBetween('created_at', [$financialYearStart, $financialYearEnd])
            ->max('max_number');

        $nextMaxNumber = $maxNumber ? $maxNumber + 1 : 1;

        // $invoiceNumber = $this->generateInvoiceNumber();

      
        $invoice = new Invoice();
        $invoice->customer       = $request->customer;
        $invoice->company        = $request->company;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->max_number     = $nextMaxNumber; 
        $invoice->invoice_date   = $request->invoice_date;
        $invoice->due_date       = $request->due_date;
        $invoice->terms          = $request->terms ?? '';
        $invoice->currency       = $request->currency ?? '';
        $invoice->created_by     = Auth::user()->id;
        $invoice->paid_amount    = $request->payment_made ?? '0';
        $invoice->type           = '1';
        $invoice->save();

        // Items
        $items = $request->items;
        foreach ($items as $item) {
            $invoice_item               = new InvoiceItem();
            $invoice_item->invoice_id   = $invoice->id;
            $invoice_item->description  = $item['description'];
            $invoice_item->hsn          = $item['hsn'];
            $invoice_item->quantity     = $item['quantity'] ?? 0;
            $invoice_item->rate         = $item['rate'];
            $invoice_item->tax_type     = $item['tax_type'] ?? 'none';
            // Calculate tax amounts based on tax type
            $subtotal = $item['quantity'] * $item['rate'];
            if ($item['tax_type'] === 'igst') {
                $invoice_item->igst = ($subtotal * 18) / 100;
                $invoice_item->sgst = 0;
                $invoice_item->cgst = 0;
            } elseif ($item['tax_type'] === 'sgst_cgst') {
                $invoice_item->igst = 0;
                $invoice_item->sgst = ($subtotal * 9) / 100;
                $invoice_item->cgst = ($subtotal * 9) / 100;
            } else {
                $invoice_item->igst = 0;
                $invoice_item->sgst = 0;
                $invoice_item->cgst = 0;
            }
            $invoice_item->total_amount = $item['total_amount'];
            $invoice_item->save();
        }

        return redirect()->route('invoice.index')->with('success', 'Invoice created successfully.');
    }

    public function show($id)
    {
        return redirect()->back();
    }
    public function edit($id)
    {
        $invoice = Invoice::with(['items'])->findOrFail($id);
        $companies = Company::where('created_by', Auth::user()->id)->get();
        $customers = Customer::where('created_by', Auth::user()->id)->get();
        return view('invoice.edit', compact('invoice', 'companies', 'customers'));
    }

    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'customer'     => 'required',
            'company'      => 'required',
            'invoice_date' => 'required',
            'due_date'     => 'required',
            'currency'     => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $invoice = Invoice::findOrFail($id);
    
       
        $invoice->customer       = $request->customer;
        $invoice->company        = $request->company;
        $invoice->invoice_date   = $request->invoice_date;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->due_date       = $request->due_date;
        $invoice->terms          = $request->terms ?? '';
        $invoice->currency       = $request->currency ?? '';
        $invoice->paid_amount    = $request->payment_made ?? '0';
        $invoice->type           = '1';
        $invoice->save();

       
        InvoiceItem::where('invoice_id', $invoice->id)->delete();

        foreach ($request->items as $item) {
            $invoice_item               = new InvoiceItem();
            $invoice_item->invoice_id   = $invoice->id;
            $invoice_item->description  = $item['description'];
            $invoice_item->hsn          = $item['hsn'];
            $invoice_item->quantity     = isset($item['quantity']) ? $item['quantity'] : 0;
            $invoice_item->rate         = $item['rate'];
            $invoice_item->tax_type     = $item['tax_type'] ?? 'none';
            // Calculate tax amounts based on tax type
            $subtotal = $item['quantity'] * $item['rate'];
            if ($item['tax_type'] === 'igst') {
                $invoice_item->igst = ($subtotal * 18) / 100;
                $invoice_item->sgst = 0;
                $invoice_item->cgst = 0;
            } elseif ($item['tax_type'] === 'sgst_cgst') {
                $invoice_item->igst = 0;
                $invoice_item->sgst = ($subtotal * 9) / 100;
                $invoice_item->cgst = ($subtotal * 9) / 100;
            } else {
                $invoice_item->igst = 0;
                $invoice_item->sgst = 0;
                $invoice_item->cgst = 0;
            }
            $invoice_item->total_amount = $item['total_amount'];
            $invoice_item->save();
        }

        return redirect()->route('invoice.index')->with('success', 'Invoice updated successfully.');
    }

    
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        InvoiceItem::where('invoice_id', $invoice->id)->delete();
        $invoice->delete();
        return redirect()->route('invoice.index')->with('success', 'Invoice deleted successfully.');
    }

    public function downloadPDF($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $invoice = Invoice::with(['company_name', 'customer_name', 'items'])->findOrFail($id);

        $pdf = Pdf::loadView('invoice.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');

       
        $safeInvoiceNumber = str_replace(['#', '/', '\\'], ['-', '-', '-'], $invoice->invoice_number);

        $fileName = 'Invoice' . $safeInvoiceNumber . '.pdf';
        return $pdf->download($fileName);
    }

    public function deleteItem($id)
    {
        $item = InvoiceItem::findOrFail($id);
        $item->delete();
        
        return response()->json(['success' => true]);
    }


    public function nonGstInvoiceCreate()
    {
        $companies = Company::where('created_by', Auth::user()->id)->get();
        $customers = Customer::where('created_by', Auth::user()->id)->get();
        $generatedInvoiceNumber = $this->generateInvoiceNumber();
        return view('invoice.non-gst-create', compact('companies', 'customers', 'generatedInvoiceNumber'));
    }

    public function nonGstInvoiceStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer'     => 'required',
            'company'      => 'required',
            'invoice_date' => 'required',
            'due_date'     => 'required',
            'currency'     => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $now = \Carbon\Carbon::now();

        // Use provided invoice number or generate new one
        // $invoiceNumber = $this->generateInvoiceNumber();

        // Financial Year calculation
        $yearStart = $now->month >= 4 ? $now->year : $now->year - 1;
        $yearEnd = $yearStart + 1;
        $financialYearStart = \Carbon\Carbon::create($yearStart, 4, 1); // April 1st
        $financialYearEnd   = \Carbon\Carbon::create($yearEnd, 3, 31, 23, 59, 59); // March 31st

        
        $maxNumber = Invoice::whereBetween('created_at', [$financialYearStart, $financialYearEnd])
            ->max('max_number');

        $nextMaxNumber = $maxNumber ? $maxNumber + 1 : 1;

      
        $invoice = new Invoice();
        $invoice->customer       = $request->customer;
        $invoice->company        = $request->company;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->max_number     = $nextMaxNumber; 
        $invoice->invoice_date   = $request->invoice_date;
        $invoice->due_date       = $request->due_date;
        $invoice->currency       = $request->currency ?? '';
        $invoice->created_by     = Auth::user()->id;
        $invoice->paid_amount    = $request->payment_made ?? '0';
        $invoice->type           = '2';
        $invoice->save();

        // Items
        $items = $request->items;
        foreach ($items as $item) {
            $invoice_item               = new InvoiceItem();
            $invoice_item->invoice_id   = $invoice->id;
            $invoice_item->description  = $item['description'];
            $invoice_item->total_amount = $item['total_amount'];
            $invoice_item->save();
        }

        return redirect()->route('invoice.index')->with('success', 'Invoice created successfully.');
    }


    public function nonGstInvoiceEdit($id)
    {
        $invoice = Invoice::with(['items'])->findOrFail($id);
        $companies = Company::where('created_by', Auth::user()->id)->get();
        $customers = Customer::where('created_by', Auth::user()->id)->get();
        return view('invoice.non-gst-edit', compact('invoice', 'companies', 'customers'));
    }


     public function nonGstInvoiceupdate(Request $request, $id)
     {
            $validator = Validator::make($request->all(), [
                'customer'     => 'required',
                'company'      => 'required',
                'invoice_date' => 'required',
                'due_date'     => 'required',
                'currency'     => 'required',
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $invoice = Invoice::findOrFail($id);
          

        
            $invoice->customer       = $request->customer;
            $invoice->company        = $request->company;
            $invoice->invoice_date   = $request->invoice_date;
            $invoice->invoice_number = $request->invoice_number;
            $invoice->due_date       = $request->due_date;
            $invoice->currency       = $request->currency ?? '';
            $invoice->paid_amount    = $request->payment_made ?? '0';
            $invoice->type           = '2';
            $invoice->save();

        
            InvoiceItem::where('invoice_id', $invoice->id)->delete();

            foreach ($request->items as $item) {
                $invoice_item               = new InvoiceItem();
                $invoice_item->invoice_id   = $invoice->id;
                $invoice_item->description  = $item['description'];
                $invoice_item->total_amount = $item['total_amount'];
                $invoice_item->save();
            }

            return redirect()->route('invoice.index')->with('success', 'Invoice updated successfully.');
     }

    public function nondownloadPDF($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $invoice = Invoice::with(['company_name', 'customer_name', 'items'])->findOrFail($id);

        $pdf = Pdf::loadView('invoice.non-gst-pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');

       
        $safeInvoiceNumber = str_replace(['#', '/', '\\'], ['-', '-', '-'], $invoice->invoice_number);
        

        $fileName = 'Invoice' . $safeInvoiceNumber . '.pdf';
        return $pdf->download($fileName);
    }

     
}
