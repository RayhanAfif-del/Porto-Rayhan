<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re: {{ $contactMessage->subject }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f4f4f5; color: #18181b; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: #0c0c0e; padding: 28px 36px; }
        .header-brand { font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -0.02em; }
        .header-brand span { color: #ff5a4d; }
        .header-sub { font-size: 11px; color: rgba(255,255,255,0.4); letter-spacing: 0.15em; text-transform: uppercase; margin-top: 2px; }
        .body { padding: 36px; }
        .greeting { font-size: 18px; font-weight: 600; color: #18181b; margin-bottom: 8px; }
        .reply-content { font-size: 14px; line-height: 1.75; color: #3f3f46; white-space: pre-line; padding: 20px; background: #fafafa; border-left: 3px solid #ff5a4d; border-radius: 8px; margin: 20px 0; }
        .divider { border: none; border-top: 1px solid #e4e4e7; margin: 28px 0; }
        .original-label { font-size: 11px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; color: #a1a1aa; margin-bottom: 12px; }
        .original-box { background: #f4f4f5; border-radius: 8px; padding: 16px 20px; }
        .original-subject { font-size: 13px; font-weight: 600; color: #3f3f46; margin-bottom: 4px; }
        .original-meta { font-size: 12px; color: #a1a1aa; margin-bottom: 12px; }
        .original-body { font-size: 13px; line-height: 1.65; color: #71717a; white-space: pre-line; }
        .footer { background: #fafafa; border-top: 1px solid #e4e4e7; padding: 20px 36px; text-align: center; }
        .footer-text { font-size: 11px; color: #a1a1aa; line-height: 1.6; }
        .footer-name { font-weight: 600; color: #71717a; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-brand">{{ $adminName }}<span>.</span></div>
            <div class="header-sub">Balasan Pesan Pribadi</div>
        </div>

        <div class="body">
            <p class="greeting">Halo, {{ $contactMessage->name }} 👋</p>
            <p style="font-size:14px;color:#71717a;margin-top:6px;">
                Terima kasih sudah menghubungi saya. Berikut balasan atas pesan Anda:
            </p>

            <div class="reply-content">{{ $replyBody }}</div>

            <hr class="divider">

            <div class="original-label">Pesan Asli Anda</div>
            <div class="original-box">
                <div class="original-subject">{{ $contactMessage->subject }}</div>
                <div class="original-meta">
                    Dikirim pada {{ $contactMessage->created_at->format('d M Y, H:i') }}
                </div>
                <div class="original-body">{{ $contactMessage->message }}</div>
            </div>
        </div>

        <div class="footer">
            <p class="footer-text">
                Email ini dikirim oleh <span class="footer-name">{{ $adminName }}</span> sebagai balasan atas pesan yang Anda kirim melalui formulir kontak.<br>
                Untuk melanjutkan percakapan, balas langsung email ini.
            </p>
        </div>
    </div>
</body>
</html>
