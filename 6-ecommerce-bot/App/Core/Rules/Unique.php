<?php

namespace App\Core\Rules;

use App\Core\Database;

class Unique implements RulesInterface
{

    public function validate($value, $rule_param = null, $form_data = []): bool
    {
        [$table, $column, $except] = explode(',', $rule_param);

        $db = new Database();
        $found = $db->prepare("SELECT `id` FROM {$table} WHERE {$column}=:{$column} LIMIT 1", [
            "{$column}" => $value
        ])->find();

        if ($found && $except && $found['id'] === (int) $except) {
            return true;
        }

        return ! $found;
    }

    public function errorMessage($field_title, $field_name, $rule_name, $rule_param): string
    {
        return "{$field_title} قبلا در سایت ثبت شده است ";
    }
}