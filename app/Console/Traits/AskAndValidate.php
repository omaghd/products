<?php

namespace App\Console\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait AskAndValidate
{
    protected function askAndValidate(string $message, string $column, Request $request): mixed
    {
        $input = $this->ask($message);

        $rules = $request->rules()[$column];

        $validator = Validator::make(
            [$column => $input],
            [$column => $rules]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return $this->askAndValidate($message, $column, $request);
        }

        return $input;
    }
}
