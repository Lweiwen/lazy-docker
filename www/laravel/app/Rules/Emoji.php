<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Emoji implements Rule
{

    /**
     * 验证是否包含emoji表情
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
//        $str = preg_replace_callback(
//            '/./u',
//            function (array $match) {
//                var_dump($match);
//                return strlen($match[0]) >= 4 ? '' : $match[0];
//            },
//            $value);
//        return $str === $value;
        $flag = true;
        preg_match_all('/./u', $value, $match);
        foreach ($match[0] as $item) {
            if (strlen($item) >= 4) {
                $flag = false;
                break;
            }
        }
        return $flag;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute不能包含表情字符';
    }
}
