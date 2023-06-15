<?php

namespace App\Console\Traits;

use Illuminate\Support\Facades\Validator;

trait AskAndValidate
{
    protected function askAndValidate(string $message, string $rules, string $column): mixed
    {
        $input = $this->ask($message);

        $validator = Validator::make(
            [$column => $input],
            [$column => $rules]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return $this->askAndValidate($message, $rules, $column);
        }

        return $input;
    }
}
