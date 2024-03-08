<?php


namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ChineseAlphaNumericTop implements Rule
{

    /**
     * 以中英文字符、数字开头
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @author LWW
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]/u', $value) ? true : false;
    }

    /**
     * 返回信息
     * @return string
     * @author LWW
     */
    public function message()
    {
        return ':attribute只能以中英文字符、数字开头';
    }
}
