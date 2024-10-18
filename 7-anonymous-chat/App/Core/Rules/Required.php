<?php

namespace App\Core\Rules;

class Required implements RulesInterface
{

    public function validate($value, $rule_param = null, $form_data = []): bool
    {
        return !empty(trim($value));
    }

    public function errorMessage($field_title, $field, $rule_name, $rule_param): string
    {
        return "وارد کردن فیلد {$field_title} ضروری است";
    }
}