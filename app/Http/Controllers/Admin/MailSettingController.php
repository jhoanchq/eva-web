<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailSetting;
use Illuminate\Http\Request;

class MailSettingController extends Controller
{
    public function index()
    {
        $settings = MailSetting::firstOrNew(['is_active' => true]);
        return view('admin.mail.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'mailer'       => 'required|string|in:smtp,sendmail,log',
            'host'         => 'required_if:mailer,smtp|string|max:255',
            'port'         => 'required_if:mailer,smtp|integer|min:1|max:65535',
            'username'     => 'nullable|string|max:255',
            'password'     => 'nullable|string',
            'encryption'   => 'nullable|string|in:tls,ssl,null',
            'from_address' => 'required|email|max:255',
            'from_name'    => 'required|string|max:255',
        ]);

        $data['is_active'] = true;

        // Encriptar password
        if (!empty($data['password'])) {
            $data['password'] = encrypt($data['password']);
        }

        $settings = MailSetting::where('is_active', true)->first();
        if ($settings) {
            // Mantener password si no se envió uno nuevo
            if (empty($request->password)) {
                unset($data['password']);
            }
            $settings->update($data);
        } else {
            if (empty($data['password'])) {
                $data['password'] = encrypt('');
            }
            MailSetting::create($data);
        }

        return redirect()->route('admin.mail.settings')
            ->with('success', 'Configuración de correo guardada correctamente.');
    }

    public function testForm()
    {
        $settings = MailSetting::getActive();
        return view('admin.mail.test', compact('settings'));
    }
}
