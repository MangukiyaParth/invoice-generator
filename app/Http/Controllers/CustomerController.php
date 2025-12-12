<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Customer::orderBy('id', 'desc');
            return DataTables::of($query)
                ->addColumn('actions', function ($customer) {
                    $editUrl = route('customers.edit', $customer->id);
                    $deleteUrl = route('customers.destroy', $customer->id);
                    $buttons = '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-primary me-2">
                            <i class="fa fa-edit me-2"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                            data-url="' . $deleteUrl . '"
                            title="Delete">
                            <i class="fa fa-trash me-2"></i> Delete
                        </button>
                    ';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('customers.index');
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $customer = new Customer();
        $customer->name = $request->name ?? null;
        $customer->email = $request->email ?? null;
        $customer->address = $request->address ?? null;
        $customer->city = $request->city ?? null;
        $customer->state = $request->state ?? null;
        $customer->country = $request->country ?? null;
        $customer->zip_code = $request->zip_code ?? null;
        $customer->gst_number = $request->gst_number ?? null;
        $customer->place_of_supply = $request->place_of_supply ?? null;
        $customer->created_by = Auth::user()->id;
        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }
    
    public function show($id)
    {
        return redirect()->back();
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $customer->name = $request->name ?? null;
        $customer->email = $request->email ?? null;
        $customer->address = $request->address ?? null;
        $customer->city = $request->city ?? null;
        $customer->state = $request->state ?? null;
        $customer->country = $request->country ?? null;
        $customer->zip_code = $request->zip_code ?? null;
        $customer->gst_number = $request->gst_number ?? null;
        $customer->place_of_supply = $request->place_of_supply ?? null;
        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function getDetails($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }
}
