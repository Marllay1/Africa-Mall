<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => PlatformSetting::current(),
            'mailer' => config('mail.default'),
            'fromAddress' => config('mail.from.address'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'maintenance_mode' => ['nullable', 'boolean'],
            'maintenance_message' => ['nullable', 'string', 'max:500'],
            'min_withdrawal_amount' => ['required', 'integer', 'min:1'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'commission_percent' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $validated['maintenance_mode'] = $request->boolean('maintenance_mode');

        $settings = PlatformSetting::current();
        $settings->fill($validated);
        $settings->save();

        return back()->with('status', 'settings-updated');
    }

    public function sendTestEmail(Request $request): RedirectResponse
    {
        try {
            Mail::raw(
                'Ceci est un email de test envoyé depuis /admin/parametres le '.now()->format('d/m/Y H:i').'.',
                function ($message) use ($request) {
                    $message->to($request->user()->email)->subject('AfricaMall — Email de test');
                }
            );
        } catch (Throwable $e) {
            report($e);

            return back()->with('status', 'test-email-failed');
        }

        return back()->with('status', 'test-email-sent');
    }

    public function logs(): View
    {
        $path = storage_path('logs/laravel.log');
        $lines = [];

        if (is_file($path)) {
            $content = file_get_contents($path, false, null, max(0, filesize($path) - 50_000));
            $lines = array_slice(array_filter(explode("\n", $content)), -200);
            $lines = array_reverse($lines);
        }

        return view('admin.settings.logs', ['lines' => $lines]);
    }
}
