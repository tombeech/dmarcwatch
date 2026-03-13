<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyInboundWebhook
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('dmarcwatch.inbound_email.mailgun_secret');

        if (! $secret) {
            return $next($request);
        }

        $timestamp = $request->input('timestamp');
        $token = $request->input('token');
        $signature = $request->input('signature');

        if (! $timestamp || ! $token || ! $signature) {
            abort(403, 'Missing Mailgun signature parameters');
        }

        $computed = hash_hmac('sha256', $timestamp . $token, $secret);

        if (! hash_equals($computed, $signature)) {
            abort(403, 'Invalid Mailgun signature');
        }

        // Reject if timestamp is more than 5 minutes old
        if (abs(time() - (int) $timestamp) > 300) {
            abort(403, 'Mailgun signature timestamp expired');
        }

        return $next($request);
    }
}
