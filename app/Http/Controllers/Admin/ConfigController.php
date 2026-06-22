<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class ConfigController extends Controller
{
    public function index(Request $request)
    {
        $tab      = $request->get('tab', 'gmail');
        $admin    = auth()->user();
        $types    = Setting::templateTypes();
        $templates = [];

        foreach (array_keys($types) as $type) {
            $templates[$type] = Setting::emailTemplate($type);
        }

        $gmailConfigured = !empty(config('services.gmail.refresh_token'));

        return view('admin.config', compact('tab', 'admin', 'types', 'templates', 'gmailConfigured'));
    }

    public function updateAccount(Request $request)
    {
        $admin = auth()->user();

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $admin->id],
        ];

        if ($request->filled('password')) {
            $rules['password']              = ['required', 'confirmed', Password::min(8)];
            $rules['password_confirmation'] = ['required'];
        }

        $validated = $request->validate($rules);

        $admin->name  = $validated['name'];
        $admin->email = $validated['email'];

        if ($request->filled('password')) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('admin.config.index', ['tab' => 'account'])
            ->with('success_account', 'Account settings saved successfully.');
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);

        $to      = $request->input('test_email');
        $subject = '[DMS Docman] Test Email — ' . now()->format('d M Y H:i');

        try {
            Mail::raw(
                "This is a test email from DMS Docman.\n\n"
                . "If you received this, your mail configuration is working correctly.\n\n"
                . "Sent at: " . now()->toDateTimeString() . "\nMailer: " . config('mail.default'),
                function ($message) use ($to, $subject) {
                    $message->to($to)->subject($subject);
                }
            );

            return redirect()->route('admin.config.index', ['tab' => 'gmail'])
                ->with('test_success', "Test email sent successfully to {$to}.");
        } catch (\Throwable $e) {
            Log::error('Test email failed: ' . $e->getMessage());

            return redirect()->route('admin.config.index', ['tab' => 'gmail'])
                ->with('test_error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function updateTemplates(Request $request)
    {
        $types = array_keys(Setting::templateTypes());

        foreach ($types as $type) {
            $subject = $request->input("templates.{$type}.subject", '');
            $body    = $request->input("templates.{$type}.body", '');

            if ($subject !== '' || $body !== '') {
                Setting::set('email_template_' . $type, json_encode([
                    'subject' => $subject,
                    'body'    => $body,
                ]));
            }
        }

        return redirect()->route('admin.config.index', ['tab' => 'templates'])
            ->with('success_templates', 'Email templates saved successfully.');
    }
}
