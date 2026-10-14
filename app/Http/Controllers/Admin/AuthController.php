<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(Request $request): View
    {
        return view('admin.login', ['maskedIp' => $this->maskIp((string) $request->ip())]);
    }

    /** Samarkan sebagian IP agar aman ditampilkan, mis. 103.84.x.x */
    private function maskIp(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            [$a, $b] = explode('.', $ip);

            return "{$a}.{$b}.x.x";
        }

        $groups = explode(':', $ip);

        return ($groups[0] ?? '').':'.($groups[1] ?? '').':x:x';
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Hanya akun admin yang boleh masuk.
        if (! Auth::attempt([...$credentials, 'is_admin' => true], $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
