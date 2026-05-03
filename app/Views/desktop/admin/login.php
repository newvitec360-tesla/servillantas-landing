<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($title ?? 'Acceso Gestor Landing') ?></title>
  <style>
    body {
        margin: 0; padding: 0; font-family: system-ui, -apple-system, sans-serif;
        background-color: #f4f4f5; display: flex; align-items: center; justify-content: center; height: 100vh;
    }
    .login-card {
        background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        width: 100%; max-width: 380px; text-align: center;
    }
    .login-card h1 { margin: 0 0 10px; font-size: 24px; color: #111827; }
    .login-card p { margin: 0 0 30px; font-size: 14px; color: #6b7280; }
    .input-group { margin-bottom: 20px; text-align: left; }
    .input-group label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: 600; color: #374151; }
    .input-group input { 
        width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #d1d5db; 
        border-radius: 6px; font-size: 14px; outline: none; transition: border-color 0.2s;
    }
    .input-group input:focus { border-color: #ef4444; }
    .btn {
        width: 100%; background: #ef4444; color: #fff; border: none; padding: 12px;
        border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.2s;
    }
    .btn:hover { background: #dc2626; }
    .error-msg { background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 6px; font-size: 13px; margin-bottom: 20px; }
  </style>
</head>
<body>
  <div class="login-card">
    <h1>Gestor Landing</h1>
    <p>Acceso exclusivo de administración</p>

    <?php if (!empty($error)): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="/index.php?r=/admin-landing/login" method="POST">
        <div class="input-group">
            <label for="username">Usuario de la Landing</label>
            <input type="text" id="username" name="username" required autocomplete="username" />
        </div>
        <div class="input-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required autocomplete="current-password" />
        </div>
        <button type="submit" class="btn">Ingresar al Gestor</button>
    </form>
  </div>
</body>
</html>
