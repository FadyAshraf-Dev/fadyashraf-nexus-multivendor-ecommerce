<?php
declare(strict_types=1);
class Asset{
    /**
     * Generates a cache-busted URL for static assets (CSS, JS)
     * * @param string $relativePath Path to the asset relative to the admin folder (e.g., 'css/styles.css')
     * @return string The asset path appended with its modification timestamp
     */
    public static function url(string $relativePath): string {
        // __DIR__ is the classes/ folder. We step out to find the physical file inside admin/
        $physicalPath = __DIR__ . '/../admin/' . $relativePath;

        if (file_exists($physicalPath)) {
            $timestamp = filemtime($physicalPath);
            return $relativePath . '?v=' . $timestamp;
        }

        // Fallback: If the file is missing, return the original string so the layout doesn't completely die
        return $relativePath;
    }

}