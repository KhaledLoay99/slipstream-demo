<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCategory;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('category')->get();
        $categories = CustomerCategory::all();
        return view('customers.index', compact('customers', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'reference' => 'required',
            'customer_category_id' => 'required|exists:customer_categories,id',
            'start_date' => 'required|date',
            'description' => 'required',
        ]);
        Customer::create($request->all());
        return redirect()->back()->with('success', 'Customer created.');
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'reference' => 'required',
            'customer_category_id' => 'required|exists:customer_categories,id',
            'start_date' => 'required|date',
            'description' => 'required',
        ]);
        $customer->update($request->all());
        return redirect()->back()->with('success', 'Customer updated.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->back()->with('success', 'Customer deleted.');
    }
}
