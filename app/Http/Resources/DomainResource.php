<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DomainResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'rua_address' => $this->rua_address,
            'dmarc_policy' => $this->dmarc_policy,
            'spf_status' => $this->spf_status,
            'dkim_status' => $this->dkim_status,
            'compliance_score' => $this->compliance_score,
            'last_dns_check_at' => $this->last_dns_check_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
