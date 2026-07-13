<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><style>
body{font-family:'Segoe UI',sans-serif;background:#f4f4f4;margin:0;padding:0}
.container{max-width:600px;margin:2em auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.1)}
.header{background:linear-gradient(135deg,#0a0e1a,#1a1a4e);padding:2em;text-align:center}
.header h1{color:#00d4ff;margin:0;font-size:1.5em}
.tag{display:inline-block;background:rgba(0,212,255,0.15);color:#00d4ff;padding:0.2em 0.8em;border-radius:20px;font-size:0.75em;margin-top:0.5em}
.body{padding:2em;color:#333}
.body p{line-height:1.6;font-size:1em;white-space:pre-wrap}
.footer{background:#f4f4f4;padding:1.5em;text-align:center;font-size:0.8em;color:#888}
</style></head>
<body>
<div class="container">
    <div class="header">
        <h1>📬 {{ $subjectText }}</h1>
        @if($tag)<div class="tag">{{ $tag }}</div>@endif
    </div>
    <div class="body">
        <p>{{ $messageText }}</p>
    </div>
    <div class="footer">
        Enviado desde EVA-WEB — Evaluación y Control de Servicios Web<br>
        IESTP Jorge Basadre
    </div>
</div>
</body>
</html>
