<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MessageReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        return view('admin.messages.index', ['messages' => ContactMessage::latest()->paginate(20)]);
    }

    public function show(ContactMessage $message): View
    {
        if (! $message->read_at) {
            $message->update(['read_at' => now()]);
        }

        return view('admin.messages.show', ['message' => $message]);
    }

    public function reply(Request $request, ContactMessage $message): RedirectResponse
    {
        $validated = $request->validate([
            'reply_body' => ['required', 'string', 'min:10', 'max:5000'],
        ], [
            'reply_body.required' => 'Isi balasan tidak boleh kosong.',
            'reply_body.min' => 'Balasan terlalu singkat, minimal 10 karakter.',
        ]);

        $adminName = auth()->user()?->name ?? config('app.name');

        Mail::to($message->email, $message->name)
            ->send(new MessageReplyMail($message, $validated['reply_body'], $adminName));

        // Mark as read if not already
        if (! $message->read_at) {
            $message->update(['read_at' => now()]);
        }

        return redirect()
            ->route('admin.messages.show', $message)
            ->with('reply_sent', 'Balasan berhasil dikirim ke '.$message->email.'.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('status', 'Pesan dihapus.');
    }
}
