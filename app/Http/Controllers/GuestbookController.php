<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\GuestbookMessage;
use Illuminate\Support\Facades\Storage;

class GuestbookController extends Controller
{
    private $editMinutes = 5;

    public function index(Request $request)
    {
        return Inertia::render('Guestbook/Index', [
            'messages' => GuestbookMessage::all(),
            'userIp'=> $request->ip(),
            'editMinutes' => $this->editMinutes
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

        $message = GuestbookMessage::create($request->only([
            'name',
            'email',
            'message'
        ]));

        if ($request->hasFile('image')) {
            $message->image = Storage::disk('public')
                ->put('images/guestbook', request()->file('image'));
        }

        $message->ip = $request->ip();
        $message->save();

        return to_route('guestbook');
        //return Redirect::route('guestbook')->with('success', 'Contact created.');
    }

    public function edit(GuestbookMessage $message)
    {
        return Inertia::render('Guestbook/Edit', [
            'message' => [
                'id' => $message->id,
                'name' => $message->name,
                'email' => $message->email,
                'message' => $message->message,
                'image' => $message->image,
                'ip' => $message->ip,
                'created_at' => $message->created_at
            ],
        ]);
    }

    public function update(GuestbookMessage $message, Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $message = GuestbookMessage::findOrFail($message->id);
        $message->name = $request->get('name');
        $message->email = $request->get('email');
        $message->message = $request->get('message');
        $message->save();

        return to_route('guestbook');
//        return Redirect::back()->with('success', 'Contact updated.');
    }

    public function destroy(GuestbookMessage $message, Request $request)
    {
        if($message->ip === $request->ip()){
            $message->delete();
        }

        return to_route('guestbook');
//        return Redirect::back()->with('success', 'Contact deleted.');
    }
}
