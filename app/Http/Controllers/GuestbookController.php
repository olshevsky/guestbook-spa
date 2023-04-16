<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GuestbookMessage;
use Illuminate\Support\Facades\Storage;
use App\Rules\Recaptcha;

class GuestbookController extends Controller
{
    public function index(Request $request)
    {
        $orderBy = $request->get('orderBy', 'created_at');
        $order = $request->query('order', 'desc');

        return Inertia::render('Guestbook/Index', [
            'messages' => GuestbookMessage::orderBy($orderBy, $order)
                ->paginate(10),
            'orderBy' => $orderBy,
            'order' => $order,
            'userIp'=> $request->ip(),
            'editMinutes' => GuestbookMessage::$editMinutes,
            'isAdmin' => $this->isAdmin(),
            'user' => \Auth::user()
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
            'captcha'  => [new Recaptcha],
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
    }

    public function edit(GuestbookMessage $message, Request $request)
    {
        if(!$message->isEditable($request->ip()) &&
            !$this->isAdmin())
                return to_route('guestbook');

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
            'captcha'  => [new Recaptcha],
        ]);

        $message = GuestbookMessage::findOrFail($message->id);
        if(!$message->isEditable($request->ip()) &&
            !$this->isAdmin())
                return to_route('guestbook');

        $message->name = $request->get('name');
        $message->email = $request->get('email');
        $message->message = $request->get('message');
        $message->edited_at = now();

        if ($request->hasFile('image')) {
            $message->image = Storage::disk('public')
                ->put('images/guestbook', request()->file('image'));
        }

        $message->save();

        return to_route('guestbook');
    }

    public function destroy(GuestbookMessage $message, Request $request)
    {
        if($message->isEditable($request->ip()) || $this->isAdmin()){
            $message->delete();
        }

        return to_route('guestbook');
    }

    private function isAdmin(): bool
    {
        return \Auth::user()?->is_admin ?? false;
    }
}
