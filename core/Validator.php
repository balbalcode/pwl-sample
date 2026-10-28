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
        'min'       => [self::class, 'validateMinMax'],
        'max'       => [self::class, 'validateMinMax'],
        'min_length'       => [self::class, 'validateMinMaxLength'],
        'max_length'       => [self::class, 'validateMinMaxLength'],
    ];

    private const BOUND_RULES = [
        'min'           => true,
        'max'           => true,
        'min_length'    => true,
        'max_length'    => true,
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

    protected static function validateMinMax(mixed $data, string $bound, int $length): bool {
        if (is_numeric($data)) {
            $value = floatval($data);
        } else {
            return false;
        }

        return $bound === 'min' ? $value >= $length : $value <= $length;
    }

    protected static function validateMinMaxLength(mixed $data, string $bound, int $length): bool {
        if (is_string($data)) {
            $value = strlen($data);
        } else {
            return false;
        }

        return $bound === 'min_length' ? $value >= $length : $value <= $length;
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

    private static function parseRule(string $rule): array {
        $sep = strpos($rule, ':'); // find first ':'
        if($sep === false) return [$rule, null]; // no arg

        $name = substr($rule, 0, $sep);
        $arg = substr($rule, $sep + 1); // pass all after ':'

        return [
            $name,
            ctype_digit($arg) // check is digit
                ? (int) $arg // convert to int
                : $arg // pass as-is
        ];
    }

    private static function passes(mixed $data, string $rule): bool {
        [$name, $arg] = self::parseRule($rule);
        $check = self::RULES[$name] ?? null;

        // rule not registered
        if ($check === null) return true;

        // no arg parsed
        if($arg === null) return $check($data);

        // registered as bound rules
        if(isset(self::BOUND_RULES[$name]))
            return $check($data, $name, $arg);

        return $check($data, $arg); // pass arg
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
