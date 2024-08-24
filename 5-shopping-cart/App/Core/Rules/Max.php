<?php

namespace App\Core\Rules;

class Max implements RulesInterface
{

    public function validate($value, $rule_param = null, $form_data = []): bool
    {
        return strlen($value) <= $rule_param;
    }

    public function errorMessage($field_title, $field, $rule_name, $rule_param): string
    {
        return "{$field_title} باید حداکثر از {$rule_param} کاراکتر باشد";
    }
}