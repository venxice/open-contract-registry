<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login | Open Contract Register</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%2318334f'/%3E%3Crect x='13' y='10' width='38' height='44' rx='4' fill='none' stroke='%23f1eadf' stroke-width='4'/%3E%3Cpath d='M21 20h22M21 29h22M21 38h11M37 38h6M21 47h22' fill='none' stroke='%23d29a3d' stroke-linecap='round' stroke-width='3'/%3E%3C/svg%3E" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f5f3ef; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        .login-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.08); width: 100%; max-width: 420px; padding: 40px; }
        .brand { text-align: center; margin-bottom: 32px; }
        .brand-icon { width: 56px; height: 56px; background: #18334f; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px; }
        .brand-icon i { color: #f1eadf; font-size: 24px; }
        .brand h1 { font-size: 20px; color: #18334f; font-weight: 700; margin-bottom: 4px; }
        .brand p { font-size: 13px; color: #888; }
        .form-label { font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; }
        .form-control { border-radius: 8px; border: 1px solid #ddd; padding: 10px 14px; font-size: 14px; }
        .form-control:focus { border-color: #18334f; box-shadow: 0 0 0 3px rgba(24,51,79,.1); }
        .btn-login { width: 100%; padding: 11px; background: #18334f; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .btn-login:hover { background: #0f2236; }
        .error-msg { background: #fef2f2; color: #dc2626; border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-bottom: 16px; display: none; }
        .error-msg.show { display: block; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-building"></i></div>
            <h1>Open Contract Register</h1>
            <p>Administrator sign in</p>
        </div>
        <div id="error" class="error-msg"></div>
        <form onsubmit="doLogin(event)">
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" id="email" class="form-control" required placeholder="admin@example.com" autofocus />
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" id="password" class="form-control" required placeholder="Enter password" />
            </div>
            <button type="submit" class="btn-login" id="btn">Sign in</button>
        </form>
    </div>
    <script>
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
                    err.textContent = data.error || 'Invalid credentials';
                    err.classList.add('show');
                }
            } catch(e) {
                err.textContent = 'Connection error';
                err.classList.add('show');
            }
            btn.disabled = false; btn.textContent = 'Sign in';
        }
    </script>
</body>
</html>
