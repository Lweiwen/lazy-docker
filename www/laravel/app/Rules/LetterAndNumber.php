<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LetterAndNumber implements  Rule
{
    /**
     * 验证是否包含除了英文和数字得字符
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^[a-zA-z0-9]+$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute只允许数字和字母组合';
    }

}