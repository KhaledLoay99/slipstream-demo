<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'customer_id' => 'required|exists:customers,id',
        ]);
        Contact::create($request->all());
        return redirect()->back()->with('success', 'Contact created.');
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'customer_id' => 'required|exists:customers,id',
        ]);
        $contact->update($request->all());
        return redirect()->back()->with('success', 'Contact updated.');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->back()->with('success', 'Contact deleted.');
    }
}
