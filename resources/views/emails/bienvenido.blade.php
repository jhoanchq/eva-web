<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 2em auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #0a0e1a, #1a1a4e); padding: 2em; text-align: center; }
        .header h1 { color: #00d4ff; margin: 0; font-size: 1.5em; }
        .body { padding: 2em; color: #333; }
        .body p { line-height: 1.6; font-size: 1em; }
        .footer { background: #f4f4f4; padding: 1.5em; text-align: center; font-size: 0.8em; color: #888; }
        .badge { display: inline-block; background: #00d4ff; color: #fff; padding: 0.3em 1em; border-radius: 20px; font-size: 0.8em; margin-top: 1em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 ¡Bienvenido, {{ $user->name }}!</h1>
        </div>
        <div class="body">
            <p>Gracias por registrarte en <strong>EVA-WEB</strong>, la plataforma de evaluación y control de servicios web.</p>
            <p>Tu cuenta ha sido creada exitosamente con el correo:</p>
            <p style="text-align:center;font-size:1.1em;color:#00d4ff;"><strong>{{ $user->email }}</strong></p>
            <p>Ya puedes iniciar sesión y empezar a probar los endpoints de la API.</p>
            <p style="text-align:center;">
                <span class="badge">API Key generada automáticamente</span>
            </p>
            <p style="margin-top:1.5em;font-size:0.9em;color:#888;">Si no creaste esta cuenta, ignora este mensaje.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} EVA-WEB — Evaluación y Control de Servicios Web<br>
            IESTP Jorge Basadre
        </div>
    </div>
</body>
</html>
