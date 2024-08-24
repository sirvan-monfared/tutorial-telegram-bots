<?php

namespace App\Core;
use Exception;

class Validator
{
    protected array $errors = [];
    public function __construct(protected array $data, protected array $rules, protected array $names = [])
    {
        $this->validate();
    }

    /**
     * @throws Exception
     */
    public function validate(): void
    {
        foreach($this->rules as $field => $rules) {
            $value = $this->data[$field];

            foreach($rules as $rule) {
                [$rule_name, $rule_param] = explode(':', $rule);

                $rule_class = 'App\Core\Rules\\' . ucwords($rule_name);

                if (! class_exists($rule_class)) {
                    var_dump($rule_class);
                    throw new Exception("Rule Class Not Found");
                }

                $ruleClassInstance = new $rule_class;

                if (! $ruleClassInstance->validate($value, $rule_param, $this->data)) {
                    $field_title = $this->fieldTitle($field, $rule_name, $rule_param);

                    $this->errors[$field] = $ruleClassInstance->errorMessage($field_title, $field, $rule_name, $rule_param);
                    break;
                }
            }
        }
    }

    public function passed(): bool
    {
        return ! $this->errors;
    }

    public function failed(): bool
    {
        return ! $this->passed();
    }

    public function addError($key, $message): void
    {
        $this->errors[$key] = $message;
    }

    public function errors(): array
    {
        return $this->errors;
    }
    private function fieldTitle(string $field, string $rule_name, ?string $rule_param = null): string
    {
        $field_title = $this->names[$field] ?? $field;

        if ($rule_name === 'confirm') {
            $field_title = $this->names[$rule_param] ?? $rule_param;
        }

        return $field_title;
    }


}