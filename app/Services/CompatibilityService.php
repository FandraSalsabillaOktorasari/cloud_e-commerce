<?php

namespace App\Services;

class CompatibilityService
{
    /**
     * Slots required for a PC build.
     */
    public const SLOTS = ['cpu', 'motherboard', 'ram', 'gpu', 'storage', 'psu'];

    /**
     * Check compatibility between selected parts.
     * Returns an array of warnings/errors.
     */
    public function checkCompatibility(array $build): array
    {
        $warnings = [];

        $cpu = $build['cpu'] ?? null;
        $mobo = $build['motherboard'] ?? null;
        $ram = $build['ram'] ?? null;
        $gpu = $build['gpu'] ?? null;
        $psu = $build['psu'] ?? null;

        // CPU ↔ Motherboard socket match
        if ($cpu && $mobo) {
            if ($cpu->socket_type && $mobo->socket_type && $cpu->socket_type !== $mobo->socket_type) {
                $warnings[] = [
                    'type' => 'error',
                    'message' => "Socket Mismatch: CPU ({$cpu->socket_type}) is incompatible with Motherboard ({$mobo->socket_type}).",
                    'parts' => ['cpu', 'motherboard'],
                ];
            }
        }

        // RAM ↔ Motherboard type match
        if ($ram && $mobo) {
            if ($ram->memory_type && $mobo->memory_type && $ram->memory_type !== $mobo->memory_type) {
                $warnings[] = [
                    'type' => 'error',
                    'message' => "Memory Mismatch: RAM ({$ram->memory_type}) is incompatible with Motherboard ({$mobo->memory_type} required).",
                    'parts' => ['ram', 'motherboard'],
                ];
            }
        }

        // PSU Capacity Check
        if ($psu) {
            $draw = $this->getEstimatedWattage($build);
            if ($psu->tdp_watts && $draw > $psu->tdp_watts) {
                $warnings[] = [
                    'type' => 'warning',
                    'message' => "Power Warning: Estimated system draw ({$draw}W) exceeds PSU capacity ({$psu->tdp_watts}W).",
                    'parts' => ['gpu', 'psu', 'cpu'],
                ];
            } elseif ($psu->tdp_watts && $draw > ($psu->tdp_watts * 0.85)) {
                $warnings[] = [
                    'type' => 'info',
                    'message' => "Power Notice: System draw ({$draw}W) is approaching PSU limit. Efficiency may be reduced.",
                    'parts' => ['psu'],
                ];
            }
        }

        return $warnings;
    }

    /**
     * Calculate estimated system power draw.
     */
    public function getEstimatedWattage(array $build): int
    {
        $baseDraw = 100; // Motherboard + Storage + Fans overhead
        $cpuTdp = isset($build['cpu']) ? ($build['cpu']->tdp_watts ?? 0) : 0;
        $gpuTdp = isset($build['gpu']) ? ($build['gpu']->tdp_watts ?? 0) : 0;

        return $baseDraw + $cpuTdp + $gpuTdp;
    }
}
