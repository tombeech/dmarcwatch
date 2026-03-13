<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'domain' => $this->whenLoaded('domain', fn () => [
                'id' => $this->domain->id,
                'name' => $this->domain->name,
            ]),
            'report_id' => $this->report_id,
            'reporter_org' => $this->reporter_org,
            'reporter_email' => $this->reporter_email,
            'date_begin' => $this->date_begin?->toIso8601String(),
            'date_end' => $this->date_end?->toIso8601String(),
            'domain_policy' => $this->domain_policy,
            'total_messages' => $this->total_messages,
            'pass_count' => $this->pass_count,
            'fail_count' => $this->fail_count,
            'records' => $this->whenLoaded('records', fn () => $this->records->map(fn ($r) => [
                'source_ip' => $r->source_ip,
                'count' => $r->count,
                'disposition' => $r->disposition,
                'dkim_result' => $r->dkim_result,
                'spf_result' => $r->spf_result,
                'sending_source' => $r->sendingSource?->name,
            ])),
            'received_at' => $this->received_at?->toIso8601String(),
        ];
    }
}
