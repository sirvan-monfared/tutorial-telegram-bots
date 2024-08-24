<?php

namespace App\Core\Rules;

class Mobile implements RulesInterface
{

    public function validate($value, $rule_param = null, $form_data = []): bool
    {
        $pattern = "/^09[012349]\d{8}$/";

        return preg_match($pattern, $value);
    }

    public function errorMessage($field_title, $field, $rule_name, $rule_param): string
    {
        return "{$field_title} وارد شده معتبر نمی باشد";
    }
}