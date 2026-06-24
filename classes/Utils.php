<?php
// classes/Utils.php

class Utils {
    // Old global function turned into a static method
    public static function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    // Another helper example
    public static function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}