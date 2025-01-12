<?php

declare(strict_types=1);

if (! function_exists('formatBytes')) {
    /**
     * Format bytes to bytes, KB, MB, GB, TB
     *
     * @param int $size
     * @param int $precision
     *
     * @return string
     */
    function formatBytes($size, $precision = 2)
    {
        // Early return for 0 or negative values
        if ($size <= 0) {
            return '0 bytes';
        }

        // Define units and sizes in a constant array
        static $units = ['bytes', 'KB', 'MB', 'GB', 'TB'];
        $exp = floor(log($size, 1024));

        // Clamp the exponent to avoid array overflow
        $exp = min($exp, 4);

        // Calculate the final value using one pow() call
        $value = $size / pow(1024, $exp);

        return round($value, $precision) . ' ' . $units[$exp];
    }
}
