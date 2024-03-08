<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IDCard implements Rule
{

    /**
     * 验证身份证号码
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @author LWW
     */
    public function passes($attribute, $value)
    {
        if (strlen($value) != 18)
            return false;

        $a = str_split($value, 1);
        $w = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        $c = array(1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2);

        $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            $sum = $sum + $a[$i] * $w[$i];
        }
        $r = $sum % 11;
        $res = $c[$r];
        if ($res == $a[17]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '身份证证件号码错误';
    }
}
