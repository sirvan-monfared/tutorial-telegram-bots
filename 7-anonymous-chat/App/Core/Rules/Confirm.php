<?php

namespace App\Core\Rules;

class Confirm implements RulesInterface
{

    public function validate($value, $rule_param = null, $form_data = []): bool
    {
        return $value === $form_data[$rule_param];
    }

    public function errorMessage($field_title, $field, $rule_name, $rule_param): string
    {
        return "فیلدهای {$field_title} و تکرار {$field_title} مطابقت ندارند";
    }
}