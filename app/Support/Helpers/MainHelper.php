<?php

if (!function_exists('formatDuration')) {
    function formatDuration($minutes): string
    {
        $hours = floor($minutes / 60); // Saat
        $remainingMinutes = $minutes % 60; // Dakika

        return "{$hours} saat {$remainingMinutes} dəqiqə";
    }
}
