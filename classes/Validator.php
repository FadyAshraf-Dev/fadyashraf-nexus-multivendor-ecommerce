<?php

class Validator {
    private array $data;
    private array $errors = [];

    public function __construct(array $sourceData) {
        // Automatically sanitize basic string layers against XSS on creation
        $this->data = array_map(function($value) {
            return is_string($value) ? trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8')) : $value;
        }, $sourceData);
    }

    /**
     * The Upgraded Rule Interpreter Matrix
     * @param array $rules Maps field names to pipe-separated validation strings
     */
    public function validate(array $rules): self {
        foreach ($rules as $field => $ruleString) {
            $individualRules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($individualRules as $rule) {
                $param = null;
                if (strpos($rule, ':') !== false) {
                    list($rule, $param) = explode(':', $rule);
                }

                // ⚡ Dynamic Parameter Resolution Engine
                // Intercepts and parses literal '$_POST["key"]' strings from single quotes
                $resolvedParam = $param;
                if ($param !== null) {
                    if (preg_match('/\$_POST\[["\'](.+?)["\']\]/', $param, $matches)) {
                        $targetKey = $matches[1];
                        $resolvedParam = $this->data[$targetKey] ?? 0;
                    } elseif (isset($this->data[$param])) {
                        $resolvedParam = $this->data[$param];
                    }
                }

                // Execute the corresponding smart validation check
                switch ($rule) {
                    case 'required':
                        if ($value === null || $value === '') {
                            $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                        }
                        break;

                    case 'numeric': //
                        if ($value !== null && $value !== '' && !is_numeric($value)) {
                            $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must be a valid number.";
                        }
                        break;

                    case 'integar': // 🔢 Custom rule matching your exact structural spelling
                        if ($value !== null && $value !== '' && (!is_numeric($value) || (int)$value != $value)) {
                            $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must be a whole integer number.";
                        }
                        break;

                    case 'min': //
                        if ($value !== null && $value !== '' && is_numeric($value) && (float)$value < (float)$resolvedParam) {
                            $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " cannot be less than {$resolvedParam}.";
                        }
                        break;

                    case 'max': // 📈 Custom upper bounds limit rule
                        if ($value !== null && $value !== '' && is_numeric($value) && (float)$value > (float)$resolvedParam) {
                            $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " cannot exceed {$resolvedParam}.";
                        }
                        break;

                    case 'min_len': // 📏 String character minimum bounds check
                        if ($value !== null && $value !== '' && mb_strlen((string)$value) < (int)$resolvedParam) {
                            $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$resolvedParam} characters.";
                        }
                        break;

                    case 'max_len': // 📉 String character maximum bounds check
                        if ($value !== null && $value !== '' && mb_strlen((string)$value) > (int)$resolvedParam) {
                            $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " cannot exceed {$resolvedParam} characters.";
                        }
                        break;

                    case 'in': //
                        $allowedOptions = explode(',', $resolvedParam);
                        if ($value !== null && $value !== '' && !in_array($value, $allowedOptions)) {
                            $this->errors[$field] = "Invalid selection for " . str_replace('_', ' ', $field) . ".";
                        }
                        break;
                }

                // If a field already has an error, stop checking further rules for it
                if (isset($this->errors[$field])) {
                    break;
                }
            }
        }
        return $this;
    }

    public function fails(): bool { //
        return !empty($this->errors);
    }

    public function getErrors(): array { //
        return $this->errors;
    }

    public function getValidated(): array { //
        return $this->data;
    }
}