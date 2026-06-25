<?php

class MediaSerializer {
    private array $files = [];
    private array $errors = [];
    private int $maxSize = 2097152; // 2MB in bytes (2 * 1024 * 1024)
    
    // 🛡️ Cleaned web-native whitelist (HEIC and TIFF safely omitted)
    private array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/apng',
        'image/webp',
        'image/avif',
        'image/gif',
        'image/svg+xml',
        'image/bmp',
        'image/x-ms-bmp',   
        'image/x-icon',     
        'image/jxl',        
        'image/x-xbitmap'   
    ];

    /**
     * Accepts a raw slot from the $_FILES superglobal (e.g., $_FILES['gallery'])
     */
    public function __construct(array $rawFilesArray) {
        $this->files = $this->normalizeFilesArray($rawFilesArray);
    }

    /**
     * Executes the validation pipeline across the normalized file collection
     */
    public function validate(): self {
        if (empty($this->files)) {
            $this->errors['gallery'] = "At least one product image is required.";
            return $this;
        }

        foreach ($this->files as $index => $file) {
            // 1. Check for standard PHP upload lifecycle errors
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $this->errors["file_{$index}"] = "File '{$file['original_name']}' failed to upload correctly.";
                continue;
            }

            // 2. Enforce strict size restrictions (2MB max)
            if ($file['size'] > $this->maxSize) {
                $this->errors["file_{$index}"] = "File '{$file['original_name']}' exceeds the maximum allowed limit of 2MB.";
                continue;
            }

            // 3. Perform deep server-side binary inspection (Immune to fake file extensions)
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $realMimeType = $finfo->file($file['tmp_path']);

            if (!in_array($realMimeType, $this->allowedMimeTypes)) {
                $this->errors["file_{$index}"] = "File '{$file['original_name']}' uses an unsupported or malicious image format.";
            }
        }

        return $this;
    }

    /**
     * Commits valid files to the permanent storage disk
     * @param string $uploadDirectory Relative or absolute target path
     * @return array Sequential collection of public path strings (Index 0 is your main thumbnail!)
     */
/**
 * Commits valid files to the permanent storage disk using safe absolute pathing
 * @param string|null $uploadDirectory Optional custom path override
 */
    public function save(?string $uploadDirectory = null): array {
        $savedPaths = [];
        
        // 🛡️ If no path is provided, dynamically compute the absolute path from the project root
        if ($uploadDirectory === null) {
            // __DIR__ is /var/www/nexus/classes
            // dirname(__DIR__) takes us up to /var/www/nexus
            $uploadDirectory = dirname(__DIR__) . '/public/uploads/products/';
        }
        
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        foreach ($this->files as $file) {
            $extension = pathinfo($file['original_name'], PATHINFO_EXTENSION);
            $secureName = 'prod_' . bin2hex(random_bytes(12)) . '_' . time() . '.' . $extension;
            $destination = rtrim($uploadDirectory, '/') . '/' . $secureName;

            if (move_uploaded_file($file['tmp_path'], $destination)) {
                $savedPaths[] = 'uploads/products/' . $secureName;
            }
        }

        return $savedPaths;
    }
    public function fails(): bool {
        return !empty($this->errors);
    }

    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * Transforms the disorganized, native PHP $_FILES matrix into a clean, predictable array of files
     */
    private function normalizeFilesArray(array $rawArray): array {
        $normalized = [];

        if (!isset($rawArray['name'])) {
            return [];
        }

        // Check if it's a multi-file array structure
        if (is_array($rawArray['name'])) {
            $fileCount = count($rawArray['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                // Ignore empty or unselected file input slots safely
                if (empty($rawArray['name'][$i])) {
                    continue;
                }
                $normalized[] = [
                    'original_name' => $rawArray['name'][$i],
                    'tmp_path'      => $rawArray['tmp_name'][$i],
                    'size'          => $rawArray['size'][$i],
                    'error'         => $rawArray['error'][$i]
                ];
            }
        } else {
            // Fallback process for single file upload layouts
            if (!empty($rawArray['name'])) {
                $normalized[] = [
                    'original_name' => $rawArray['name'],
                    'tmp_path'      => $rawArray['tmp_name'],
                    'size'          => $rawArray['size'],
                    'error'         => $rawArray['error']
                ];
            }
        }

        return $normalized;
    }
}