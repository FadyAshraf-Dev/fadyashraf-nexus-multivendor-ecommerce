<?php
declare(strict_types=1);
class Html{
    /**
     * Context-aware HTML Escaping for frontend rendering output contexts (Defeats XSS)
     */
    public static function escape($data) {
        if ($data === null) return '';
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

}