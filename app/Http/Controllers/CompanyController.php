<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Company::orderBy('id', 'desc');
            return DataTables::of($query)
                ->addColumn('logo', function ($company) {
                    $imagePath = $company->logo 
                        ? asset('storage/' . ltrim($company->logo, '/')) 
                        : asset('assets/images/defaultApp.png');
                    return '<img src="'.$imagePath.'" alt="Company Logo" class="dataTable-app-img rounded" width="40" height="40">';
                })
                ->addColumn('actions', function ($company) {
                    $editUrl = route('company.edit', $company->id);
                    $deleteUrl = route('company.destroy', $company->id);
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
                ->rawColumns(['logo','actions'])
                ->make(true);
        }

        return view('company.index');
    }

    public function create()
    {
        return view('company.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'gst_number' => 'required',
            'currency' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
       
        $path = $request->file('logo')->store('uploads/images/company_logo', 'public');
        $company = new Company();
        $company->name = $request->name;
        $company->currency = $request->currency;
        $company->email = $request->email;
        $company->address = $request->address;
        $company->city = $request->city;
        $company->state = $request->state;
        $company->country = $request->country;
        $company->zip_code = $request->zip_code;
        $company->gst_number = $request->gst_number;
        $company->lut_number = $request->lut_number ?? null;
        $company->euid_number = $request->euid_number ?? null;
        $company->terms_conditions = $request->terms_conditions ?? '';
        $company->notes = $request->notes ?? '';
        $company->created_by = Auth::user()->id;
        $company->logo = $path;

        $company->save();

        return redirect()->route('company.index')->with('success', 'Company created successfully.');
    }

    public function show($id)
    {
        return redirect()->back();
    }

    public function edit(Company $company)
    {
        return view('company.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'gst_number' => 'required',
            'currency'     => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        if ($request->hasFile('logo')) {
            if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }
            $path = $request->file('logo')->store('uploads/images/company_logo', 'public');
            $company->logo = $path;
        }
        $company->currency = $request->currency;
        $company->name = $request->name;
        $company->email = $request->email;
        $company->address = $request->address;
        $company->city = $request->city;
        $company->state = $request->state;
        $company->country = $request->country;
        $company->zip_code = $request->zip_code;
        $company->gst_number = $request->gst_number;
        $company->lut_number = $request->lut_number ?? null;
        $company->euid_number = $request->euid_number ?? null;
        $company->terms_conditions = $request->terms_conditions ?? '';
        $company->notes = $request->notes ?? '';
        $company->save();
        return redirect()->route('company.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        if ($company->logo && Storage::disk('public')->exists($company->logo)) {
            Storage::disk('public')->delete($company->logo);
        }
        $company->delete();

        return redirect()->route('company.index')->with('success', 'Company deleted successfully.');
    }

    public function getDetails($id)
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }
}
