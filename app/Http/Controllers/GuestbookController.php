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

    public function destroy(GuestbookMessage $message, Request $request)
    {
        if($message->ip === $request->ip()){
            $message->delete();
        }

        return to_route('guestbook');
//        return Redirect::back()->with('success', 'Contact deleted.');
    }
}
