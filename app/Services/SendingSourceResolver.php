<?php

namespace App\Services;

use App\Models\SendingSource;
use Illuminate\Support\Facades\Cache;

class SendingSourceResolver
{
    public function resolve(string $ip): ?SendingSource
    {
        $cacheKey = 'sending_source:' . $ip;

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($ip) {
            $sources = SendingSource::all();

            foreach ($sources as $source) {
                foreach ($source->ip_ranges as $range) {
                    if ($this->ipInRange($ip, $range)) {
                        return $source;
                    }
                }
            }

            return null;
        });
    }

    protected function ipInRange(string $ip, string $range): bool
    {
        if (! str_contains($range, '/')) {
            return $ip === $range;
        }

        [$subnet, $bits] = explode('/', $range);
        $bits = (int) $bits;

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ipLong = ip2long($ip);
            $subnetLong = ip2long($subnet);
            $mask = -1 << (32 - $bits);
            return ($ipLong & $mask) === ($subnetLong & $mask);
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ipBin = inet_pton($ip);
            $subnetBin = inet_pton($subnet);
            if ($ipBin === false || $subnetBin === false) {
                return false;
            }

            $fullBits = $bits;
            $bytes = (int) floor($fullBits / 8);
            $remainBits = $fullBits % 8;

            for ($i = 0; $i < $bytes; $i++) {
                if ($ipBin[$i] !== $subnetBin[$i]) {
                    return false;
                }
            }

            if ($remainBits > 0 && $bytes < strlen($ipBin)) {
                $mask = 0xFF << (8 - $remainBits);
                if ((ord($ipBin[$bytes]) & $mask) !== (ord($subnetBin[$bytes]) & $mask)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
