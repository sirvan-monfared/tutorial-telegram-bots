<?php

namespace App\Core\Rules;

interface RulesInterface
{
    public function validate($value, $rule_param = null, $form_data = []): bool;

    public function errorMessage($field_title, $field, $rule_name, $rule_param): string;
}