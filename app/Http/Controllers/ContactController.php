<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            // Honeypot: harus kosong. Bot biasanya mengisinya.
            'website' => ['nullable', 'max:0'],
        ], [
            'website.max' => 'Pengiriman ditolak.',
        ]);

        try {
            ContactMessage::create([
                'name' => strip_tags($data['name']),
                'email' => $data['email'],
                'subject' => strip_tags($data['subject']),
                'message' => strip_tags($data['message']),
                'ip_address' => $request->ip(),
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menyimpan pesan kontak', ['error' => $e->getMessage()]);

            return redirect()->to(url()->previous().'#contact')
                ->withInput()
                ->with('contact_error', 'Pesan belum berhasil dikirim. Silakan coba lagi beberapa saat lagi.');
        }

        return redirect()->to(route('home').'#contact')
            ->with('contact_success', 'Terima kasih! Pesan Anda sudah diterima.');
    }
}
