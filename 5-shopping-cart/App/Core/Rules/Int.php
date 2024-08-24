<?php

namespace App\Core\Rules;

class Int implements RulesInterface
{

    public function validate($value, $rule_param = null, $form_data = []): bool
    {
        return is_numeric($value);
    }

    public function errorMessage($field_title, $field, $rule_name, $rule_param): string
    {
        return "{$field_title} باید از نوع عددی باشد";
    }
}