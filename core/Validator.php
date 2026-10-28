<?php declare(strict_types=1);

class Validator {
    private const RULES = [
        'required'  => [self::class, 'validateRequired'],
        'string'    => [self::class, 'validateString'],
        'numeric'   => [self::class, 'validateNumeric'],
        'int'       => [self::class, 'validateInt'],
        'float'     => [self::class, 'validateFloat'],
        'email'     => [self::class, 'validateEmail'],
        'uuid'      => [self::class, 'validateUuid'],
        'enum'      => [self::class, 'validateEnum'],
        'pattern'   => [self::class, 'validatePattern'],
    ];

    private const UUID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

    protected static function validateRequired(mixed $data): bool {
        if (!isset($data)) return false;
        return !(is_string($data) && trim($data) === '');
    }

    protected static function validateString(mixed $data): bool {
        return is_string($data);
    }

    protected static function validateNumeric(mixed $data): bool {
        return is_numeric($data);
    }

    protected static function validateInt(mixed $data): bool {
        return is_int($data);
    }

    protected static function validateFloat(mixed $data): bool {
        return is_float($data);
    }

    protected static function validateEmail(mixed $data): bool {
        return is_string($data) && filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
    }

    protected static function validateUuid(mixed $data): bool {
        return is_string($data)
            && strlen($data) === 36
            && preg_match(self::UUID_PATTERN, $data) === 1;
    }

    protected static function validateEnum(mixed $data, string $list): bool {
        return is_string($data) && in_array($data, explode(',', $list), true);
    }

    protected static function validatePattern(mixed $data, string $pattern): bool {
        return is_string($data) && preg_match("/$pattern/", $data) === 1;
    }

    private static function validateField(mixed $data, array $rules): ?string {
        $isRequired = isset($rules['required']);
        $isEmpty = !$isRequired && !self::validateRequired($data);

        foreach ($rules as $rule => $message) {
            if ($isEmpty && $rule !== 'required') return null; // ignore non-required field
            if (!self::passes($data, $rule)) return $message;
        }

        return null;
    }

    private static function passes(mixed $data, string $rule): bool {
        $check = self::RULES[$rule] ?? null;
        if ($check === null) return true; // rule not registered

        return $check($data);
    }

    public static function validate(array $data, array $fieldAndRules): ?array {
        $errors = [];
        foreach ($fieldAndRules as $field => $rules) {
            $result = self::validateField($data[$field] ?? null, $rules);
            if ($result !== null) {
                $errors[$field] = $result;
            }
        }
        return $errors === [] ? null : $errors;
    }
}
