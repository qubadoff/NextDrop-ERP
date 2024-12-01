<?php

if (!function_exists('formatDuration')) {
    function formatDuration($minutes): string
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        return "{$hours} saat {$remainingMinutes} dəqiqə";
    }
}
