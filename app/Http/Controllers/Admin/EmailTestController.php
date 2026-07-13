<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PruebaCorreo;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailTestController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'destinatarios' => 'required|string|max:1000',
            'asunto'        => 'required|string|max:255',
            'mensaje'       => 'required|string|max:5000',
            'cc'            => 'nullable|string|max:500',
            'bcc'           => 'nullable|string|max:500',
            'etiqueta'      => 'nullable|string|max:100',
        ]);

        $settings = MailSetting::getActive();
        if (!$settings) {
            return back()->with('error', 'No hay configuración de correo activa. Configúrala primero.');
        }

        // Aplicar configuración SMTP desde BD
        $settings->applyConfig();

        // Procesar destinatarios (separados por coma, punto y coma o salto de línea)
        $to = $this->parseEmails($request->destinatarios);
        if (empty($to)) {
            return back()->with('error', 'No se encontraron destinatarios válidos.');
        }

        // Procesar CC
        $cc = $this->parseEmails($request->cc);
        $bcc = $this->parseEmails($request->bcc);

        $asunto = $request->asunto;
        $mensaje = $request->mensaje;
        $etiqueta = $request->etiqueta;

        // Agregar etiqueta al asunto si existe
        if ($etiqueta) {
            $asunto = "[$etiqueta] $asunto";
        }

        $successCount = 0;
        $errorMessages = [];

        foreach ($to as $recipient) {
            try {
                $mail = new PruebaCorreo($asunto, $mensaje, $etiqueta);

                if (!empty($cc)) {
                    foreach ($cc as $ccEmail) {
                        $mail->cc($ccEmail);
                    }
                }
                if (!empty($bcc)) {
                    foreach ($bcc as $bccEmail) {
                        $mail->bcc($bccEmail);
                    }
                }

                Mail::to($recipient)->send($mail);
                $successCount++;
            } catch (\Exception $e) {
                $errorMessages[] = "$recipient: " . $e->getMessage();
                logger()->error('Error al enviar correo de prueba', [
                    'to' => $recipient,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($successCount > 0) {
            $msg = "Correo enviado exitosamente a $successCount destinatario(s).";
            if (!empty($errorMessages)) {
                $msg .= " Errores: " . implode(' | ', $errorMessages);
            }
            return back()->with('success', $msg);
        }

        return back()->with('error', 'No se pudo enviar ningún correo. Errores: ' . implode(' | ', $errorMessages));
    }

    private function parseEmails(?string $input): array
    {
        if (empty($input)) return [];

        // Separar por coma, punto y coma, o salto de línea
        $parts = preg_split('/[,;\n\r]+/', $input);
        $emails = [];

        foreach ($parts as $part) {
            $email = trim($part);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $email;
            }
        }

        return array_unique($emails);
    }
}
