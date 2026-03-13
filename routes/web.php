<?php

use App\Http\Controllers\Webhooks\InboundReportController;
use App\Http\Middleware\VerifyInboundWebhook;
use App\Livewire\Alerts\AlertChannelIndex;
use App\Livewire\Alerts\AlertLogIndex;
use App\Livewire\Alerts\AlertRuleIndex;
use App\Livewire\Billing\BillingPage;
use App\Livewire\Dashboard;
use App\Livewire\Domains\DomainCreate;
use App\Livewire\Domains\DomainIndex;
use App\Livewire\Domains\DomainShow;
use App\Livewire\Onboarding;
use App\Livewire\Reports\ReportIndex;
use App\Livewire\Reports\ReportShow;
use App\Livewire\Sources\SourceIndex;
use App\Livewire\Tools\DkimChecker;
use App\Livewire\Tools\DmarcGenerator;
use App\Livewire\Tools\SpfAnalyzerTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Marketing pages
Route::view('/', 'welcome');
Route::view('/pricing', 'pricing');
Route::view('/features', 'features');
Route::view('/about', 'about');
Route::view('/contact', 'contact');
Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'first_name' => ['required', 'string', 'max:100'],
        'last_name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:255'],
        'company' => ['nullable', 'string', 'max:100'],
        'subject' => ['required', 'string', 'in:general,support,enterprise,billing,partnership'],
        'message' => ['required', 'string', 'max:5000'],
    ]);

    Mail::raw(
        "From: {$validated['first_name']} {$validated['last_name']} <{$validated['email']}>\n"
        . "Company: " . ($validated['company'] ?? 'N/A') . "\n"
        . "Subject: {$validated['subject']}\n\n"
        . $validated['message'],
        fn ($mail) => $mail->to('hello@dmarcwatch.app')->subject("Contact: {$validated['subject']} - {$validated['first_name']} {$validated['last_name']}")
    );

    return back()->with('success', 'Your message has been sent. We will get back to you within one business day.');
})->name('contact.submit');

Route::view('/guides', 'guides');
Route::view('/guides/getting-started', 'guides.getting-started');
Route::view('/guides/understanding-spf', 'guides.understanding-spf');
Route::view('/guides/dkim-explained', 'guides.dkim-explained');
Route::view('/guides/dmarc-policy-guide', 'guides.dmarc-policy-guide');
Route::view('/guides/reading-aggregate-reports', 'guides.reading-aggregate-reports');
Route::view('/guides/email-authentication-101', 'guides.email-authentication-101');

// Webhook
Route::post('/webhooks/inbound-report', InboundReportController::class)
    ->middleware(VerifyInboundWebhook::class);

// Authenticated app routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/app/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/app/onboarding', Onboarding::class)->name('onboarding');

    Route::get('/app/domains', DomainIndex::class)->name('domains.index');
    Route::get('/app/domains/create', DomainCreate::class)->name('domains.create');
    Route::get('/app/domains/{domain}', DomainShow::class)->name('domains.show');

    Route::get('/app/reports', ReportIndex::class)->name('reports.index');
    Route::get('/app/reports/{report}', ReportShow::class)->name('reports.show');

    Route::get('/app/sources', SourceIndex::class)->name('sources.index');

    Route::get('/app/tools/dmarc-generator', DmarcGenerator::class)->name('tools.dmarc-generator');
    Route::get('/app/tools/spf-analyzer', SpfAnalyzerTool::class)->name('tools.spf-analyzer');
    Route::get('/app/tools/dkim-checker', DkimChecker::class)->name('tools.dkim-checker');

    Route::get('/app/alerts/channels', AlertChannelIndex::class)->name('alerts.channels');
    Route::get('/app/alerts/rules', AlertRuleIndex::class)->name('alerts.rules');
    Route::get('/app/alerts/logs', AlertLogIndex::class)->name('alerts.logs');

    Route::get('/app/billing', BillingPage::class)->name('billing');
});
