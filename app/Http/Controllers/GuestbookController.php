<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Guestbook;
use Illuminate\Support\Facades\Storage;

class GuestbookController extends Controller
{
    public function index(Request $request)
    {
        $url = Storage::url('file.jpg');
        return Inertia::render('Guestbook/Index', [
            'messages' => Guestbook::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('Guestbook/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $message = Guestbook::create($request->only([
            'name',
            'email',
            'message'
        ]));

        if($request->file('image')){
            $imagePath = $request->file('image')->store('image', 'public');
            $message->image = $imagePath;
        }

        $message->ip = $request->ip();
        $message->save();

        return to_route('guestbook');
        //return Redirect::route('guestbook')->with('success', 'Contact created.');
    }

    public function edit(Contact $contact)
    {
        return Inertia::render('Contacts/Edit', [
            'contact' => [
                'id' => $contact->id,
                'first_name' => $contact->first_name,
                'last_name' => $contact->last_name,
                'organization_id' => $contact->organization_id,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'address' => $contact->address,
                'city' => $contact->city,
                'region' => $contact->region,
                'country' => $contact->country,
                'postal_code' => $contact->postal_code,
                'deleted_at' => $contact->deleted_at,
            ],
            'organizations' => Auth::user()->account->organizations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function update(Contact $contact)
    {
        $contact->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organization_id' => [
                    'nullable',
                    Rule::exists('organizations', 'id')->where(fn ($query) => $query->where('account_id', Auth::user()->account_id)),
                ],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Contact updated.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return Redirect::back()->with('success', 'Contact deleted.');
    }

//    public function restore(Contact $contact)
//    {
//        $contact->restore();
//
//        return Redirect::back()->with('success', 'Contact restored.');
//    }
}
