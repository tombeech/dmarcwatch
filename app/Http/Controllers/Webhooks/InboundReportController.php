<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessInboundReport;
use App\Models\Domain;
use App\Services\ReportParser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InboundReportController extends Controller
{
    public function __invoke(Request $request, ReportParser $parser): JsonResponse
    {
        $recipient = $request->input('recipient') ?? $request->input('To') ?? '';

        // Extract the local part of the email address
        preg_match('/^([^@]+)@/', $recipient, $matches);
        $localPart = $matches[1] ?? '';

        if (empty($localPart)) {
            Log::warning('[InboundReport] No valid recipient found', ['recipient' => $recipient]);
            return response()->json(['error' => 'Invalid recipient'], 400);
        }

        $domain = Domain::withoutGlobalScope('team')
            ->where('rua_address', $recipient)
            ->orWhere('rua_address', 'like', $localPart . '@%')
            ->first();

        if (! $domain) {
            Log::warning('[InboundReport] No matching domain for recipient', ['recipient' => $recipient]);
            return response()->json(['error' => 'Unknown recipient'], 404);
        }

        Log::info('[InboundReport] Processing inbound report', [
            'recipient' => $recipient,
            'domain' => $domain->name,
        ]);

        // Try to extract XML from attachments
        $attachmentCount = (int) ($request->input('attachment-count') ?? 0);
        $processed = false;

        for ($i = 1; $i <= $attachmentCount; $i++) {
            $file = $request->file("attachment-{$i}");
            if (! $file) {
                continue;
            }

            $filename = $file->getClientOriginalName();
            $content = file_get_contents($file->getRealPath());

            try {
                $xml = $parser->decompress($content, $filename);
                ProcessInboundReport::dispatch($domain, $xml);
                $processed = true;
            } catch (\Throwable $e) {
                Log::warning('[InboundReport] Failed to process attachment', [
                    'filename' => $filename,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Fallback: try body-mime or raw body
        if (! $processed && $request->input('body-mime')) {
            try {
                $mimeParser = \ZBateson\MailMimeParser\MailMimeParser::new();
                $message = $mimeParser->parse($request->input('body-mime'), true);

                foreach ($message->getAllAttachmentParts() as $attachment) {
                    $filename = $attachment->getFilename() ?? 'report.xml';
                    $content = $attachment->getContent();

                    try {
                        $xml = $parser->decompress($content, $filename);
                        ProcessInboundReport::dispatch($domain, $xml);
                        $processed = true;
                    } catch (\Throwable $e) {
                        Log::warning('[InboundReport] Failed to process MIME attachment', [
                            'filename' => $filename,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::error('[InboundReport] MIME parsing failed', ['error' => $e->getMessage()]);
            }
        }

        if (! $processed) {
            Log::warning('[InboundReport] No processable attachments found');
            return response()->json(['message' => 'No attachments processed'], 200);
        }

        return response()->json(['message' => 'Report queued for processing'], 200);
    }
}
