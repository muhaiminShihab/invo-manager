<?php

namespace App\Services;

class ManageEnvService
{
    public function update($key, $value)
    {
        $path = app()->environmentFilePath();

        $oldValue = env($key);
        $oldValue = preg_match('/\s/', $oldValue) ? "\"{$oldValue}\""
            : $oldValue;
        $escaped = preg_quote('=' . $oldValue, '/');
        $value = preg_match('/\s/', $value) ? "\"{$value}\"" : $value;

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));

        return true;
    }
}