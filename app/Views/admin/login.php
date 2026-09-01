<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login | Open Contract Register</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%2318334f'/%3E%3Crect x='13' y='10' width='38' height='44' rx='4' fill='none' stroke='%23f1eadf' stroke-width='4'/%3E%3Cpath d='M21 20h22M21 29h22M21 38h11M37 38h6M21 47h22' fill='none' stroke='%23d29a3d' stroke-linecap='round' stroke-width='3'/%3E%3C/svg%3E" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Libre+Franklin:wght@400;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --navy: #18344f;
            --deep: #102638;
            --teal: #138b83;
            --teal-dark: #0e6965;
            --gold: #d29a3d;
            --paper: #f7f5ef;
            --surface: #fffdf8;
            --line: #d6dfdb;
            --muted: #64777c;
            --ink: #24384b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: flex;
            background: var(--paper);
            font-family: "DM Sans", system-ui, sans-serif;
            color: var(--ink);
        }
        h1, h2, h3 { font-family: "Libre Franklin", system-ui, sans-serif; letter-spacing: -0.045em; }

        .login-split { display: flex; width: 100%; min-height: 100vh; }

        /* Left panel - brand */
        .login-brand {
            background: var(--navy);
            color: #f6f1e8;
            flex: 0 0 44%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }
        .login-brand::before {
            content: "";
            position: absolute;
            top: -120px;
            right: -80px;
            width: 420px;
            height: 420px;
            border: 1px solid rgba(210, 154, 61, 0.2);
            border-radius: 50%;
        }
        .login-brand::after {
            content: "";
            position: absolute;
            bottom: -60px;
            left: -40px;
            width: 280px;
            height: 280px;
            border: 1px solid rgba(19, 139, 131, 0.15);
            border-radius: 50%;
        }
        .brand-content { position: relative; z-index: 1; }
        .brand-lockup {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 24px;
        }
        .brand-mark {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(210, 154, 61, 0.3);
            border-radius: 8px;
            color: var(--gold);
            display: inline-flex;
            font-size: 1.18rem;
            height: 38px;
            justify-content: center;
            width: 38px;
            flex-shrink: 0;
        }
        .brand-text .brand-name {
            display: block;
            font-family: "Libre Franklin";
            font-size: 0.93rem;
            font-weight: 800;
            color: #f6f1e8;
        }
        .brand-text .brand-sub {
            display: block;
            font-family: "Space Mono";
            font-size: 0.56rem;
            letter-spacing: 0.08em;
            margin-top: 3px;
            text-transform: uppercase;
            color: #7a9da3;
        }
        .brand-content h1 {
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            line-height: 1.08;
            font-weight: 800;
            margin-bottom: 14px;
        }
        .brand-content p {
            color: #9db8be;
            font-size: 0.88rem;
            line-height: 1.65;
            max-width: 340px;
        }
        .topline {
            height: 4px;
            background: linear-gradient(90deg, var(--gold) 0 33%, var(--teal) 33% 66%, var(--navy) 66%);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
        }
        .brand-footer {
            position: absolute;
            bottom: 32px;
            left: 60px;
            right: 60px;
            z-index: 1;
        }
        .brand-footer span {
            color: #5e8189;
            font-family: "Space Mono";
            font-size: 0.6rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* Right panel - form */
        .login-form-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .login-card { width: 100%; max-width: 380px; }
        .login-header { margin-bottom: 32px; }
        .login-header .eyebrow {
            font-family: "Space Mono";
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            color: var(--teal-dark);
            margin-bottom: 10px;
        }
        .login-header h2 { font-size: 1.5rem; font-weight: 800; margin-bottom: 6px; }
        .login-header p { color: var(--muted); font-size: 0.85rem; }

        .form-label {
            font-family: "Space Mono";
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 7px;
        }
        .form-control {
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 0.88rem;
            font-family: "DM Sans";
            background: var(--surface);
            color: var(--ink);
        }
        .form-control:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(19, 139, 131, 0.1);
            outline: none;
        }
        .form-control::placeholder { color: #b5c2c0; }

        .pw-wrap {
            position: relative;
        }
        .pw-wrap .form-control {
            padding-right: 42px;
        }
        .pw-toggle {
            position: absolute;
            right: 1px;
            top: 1px;
            bottom: 1px;
            width: 40px;
            background: none;
            border: 0;
            border-radius: 0 7px 7px 0;
            color: var(--muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        .pw-toggle:hover { color: var(--ink); }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background: var(--teal);
            border: 1px solid var(--teal);
            border-radius: 8px;
            color: #fff;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-primary:hover { background: var(--teal-dark); border-color: var(--teal-dark); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 24px 0;
            color: var(--muted);
            font-size: 0.72rem;
        }
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--line);
        }

        .btn-google {
            width: 100%;
            padding: 11px;
            background: #fff;
            border: 1px solid #dadce0;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 500;
            color: #3c4043;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.15s, box-shadow 0.15s;
        }
        .btn-google:hover { background: #f8f9fa; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        .btn-google svg { width: 18px; height: 18px; }

        .error-msg {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 0.8rem;
            margin-bottom: 18px;
            display: none;
        }
        .error-msg.show { display: flex; align-items: center; gap: 8px; }

        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: var(--teal-dark);
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
        }
        .forgot-link:hover { color: var(--teal); }

        @media (max-width: 991px) {
            .login-brand { display: none; }
            .login-form-wrap { padding: 24px; }
        }
    </style>
</head>
<body>
    <div class="login-split">
        <div class="login-brand">
            <div class="topline"></div>
            <div class="brand-content">
                <div class="brand-lockup">
                    <div class="brand-mark"><i class="bi bi-building"></i></div>
                    <div class="brand-text">
                        <span class="brand-name">Open Contract Register</span>
                        <span class="brand-sub">Public Bidding Portal</span>
                    </div>
                </div>
                <h1>Administrator<br>Console</h1>
                <p>Manage and publish awarded government contracts. Sign in to access the admin workspace.</p>
            </div>
            <div class="brand-footer">
                <span>Public Bidding Portal &mdash; Administrator Access</span>
            </div>
        </div>
        <div class="login-form-wrap">
            <div class="login-card">
                <div class="login-header">
                    <div class="eyebrow">Administrator access</div>
                    <h2>Sign in</h2>
                    <p>Enter your credentials to access the admin console.</p>
                </div>

                <div id="error" class="error-msg">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span id="error-text"></span>
                </div>

                <form onsubmit="doLogin(event)">
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" id="email" class="form-control" required placeholder="admin@example.com" autocomplete="email" autofocus />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="pw-wrap">
                            <input type="password" id="password" class="form-control" required placeholder="Enter password" autocomplete="current-password" />
                            <button type="button" class="pw-toggle" onclick="togglePw()" aria-label="Toggle password visibility"><i class="bi bi-eye" id="pw-icon"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary" id="btn">Sign in</button>
                </form>

                <div class="divider">or continue with</div>

                <div id="google-btn-wrap">
                    <button type="button" class="btn-google" id="google-btn" onclick="googleSignIn()">
                        <svg viewBox="0 0 24 24" width="18" height="18"><path fill="#4285F4" d="M23.74 12.27c0-.84-.08-1.65-.22-2.44H12v4.63h6.62a5.66 5.66 0 0 1-2.45 3.7v3.09h3.97c2.33-2.14 3.68-5.3 3.68-8.98z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.97-3.09c-1.09.73-2.49 1.16-3.96 1.16-3.05 0-5.63-2.06-6.55-4.83H1.38v3.19A11.99 11.99 0 0 0 12 24z"/><path fill="#FBBC05" d="M5.45 14.33A7.19 7.19 0 0 1 5.07 12c0-.81.14-1.6.38-2.33V6.48H1.38A11.99 11.99 0 0 0 0 12c0 1.94.46 3.78 1.38 5.4l4.07-3.07z"/><path fill="#EA4335" d="M12 4.75c1.76 0 3.34.61 4.58 1.8l3.44-3.44C17.93 1.18 15.22 0 12 0A11.99 11.99 0 0 0 1.38 6.48l4.07 3.19C6.36 6.81 8.95 4.75 12 4.75z"/></svg>
                        Sign in with Google
                    </button>
                </div>

                <a href="/" class="forgot-link"><i class="bi bi-arrow-left me-1"></i>Back to public register</a>
            </div>
        </div>
    </div>

    <script>
        const GOOGLE_CLIENT_ID = '<?= env('app.googleClientId', '') ?>';
        const GOOGLE_REDIRECT = '<?= base_url('api/auth/google/callback') ?>';

        function googleSignIn() {
            const params = new URLSearchParams({
                client_id: GOOGLE_CLIENT_ID,
                redirect_uri: GOOGLE_REDIRECT,
                response_type: 'code',
                scope: 'openid email profile',
                access_type: 'offline',
                prompt: 'select_account'
            });
            window.location.href = 'https://accounts.google.com/o/oauth2/v2/auth?' + params.toString();
        }

        async function doLogin(e) {
            e.preventDefault();
            const btn = document.getElementById('btn');
            const err = document.getElementById('error');
            btn.disabled = true; btn.textContent = 'Signing in...';
            err.classList.remove('show');
            try {
                const res = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value
                    })
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    window.location.href = '/admin';
                } else {
                    document.getElementById('error-text').textContent = data.error || 'Invalid credentials';
                    err.classList.add('show');
                }
            } catch(e) {
                document.getElementById('error-text').textContent = 'Connection error';
                err.classList.add('show');
            }
            btn.disabled = false; btn.textContent = 'Sign in';
        }

        window.addEventListener('load', () => {
            const err = new URLSearchParams(window.location.search).get('error');
            if (err) {
                document.getElementById('error-text').textContent = decodeURIComponent(err);
                document.getElementById('error').classList.add('show');
            }
        });

        function togglePw() {
            const input = document.getElementById('password');
            const icon = document.getElementById('pw-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>
