<?php

declare(strict_types=1);

namespace App\Kernel\LJConvert;

use function Hyperf\Config\config;
use function Hyperf\Support\env;

class LJConvert
{
    /**
     * 字符串形式的字母表
     * @var string
     */
    private string $map = '';
    /**
     * 数组形式的字母表
     * @var array
     */
    private array $arrMap = [];
    /**
     * 加密参数
     * @var array
     */
    private array $convertSecrets = [];

    /**
     * 工具初始化，
     * lj-convert配置文件中 必须存在 加密参数，以及62位，无重复的，只包含数字和字母的字母表
     * @throws \Exception
     */
    public function __construct()
    {
        $config = config('lj-convert');
        if (env('app_env') == 'production') {
            $s = $config['default_secret'];
        } else {
            $s = $config['local_default_secret'];
        }
        extract($s);
        //设置加密字母表
        $map = mb_convert_encoding($map, 'UTF-8', mb_detect_encoding($map));
        if (preg_match('/^[0-9a-zA-Z]{62}$/', $map) == 0) {
            throw new \Exception('convert service map invalid');
        }

        $map = preg_split('/(?!^)(?=.)/u', $map) ?: [];
        if (!empty($map)) {
            $map = array_unique($map);
        }
        if (!is_array($map) || count($map) != 62) {
            throw new \Exception('convert service map string not unique');
        }
        $this->arrMap = $map;
        $this->map = implode('', $map);
        //设置加密参数
        $this->setSecret($length, $step, $max, $min);
    }

    /**
     * 设定加密参数
     * @param int $convertLength
     * @param string $step
     * @param string $max
     * @param string $min
     * @param bool $force //一般情况下不能强行修改已设置的加密参数，除非有特殊情况要重置
     * @throws \Exception
     */
    public function setSecret(int $convertLength, string $step, string $max, string $min, bool $force = false)
    {
        if (isset($this->convertSecrets[$convertLength]) && !$force) {
            throw new \Exception('convert service the length secret has existed');
        }
        if ($convertLength <= 0 || $convertLength >= 62) {
            throw new \Exception('convert service length is invalid');
        }
        if (ctype_digit($step) === false) {
            throw new \Exception('convert service step invalid');
        }
        if (ctype_digit($max) === false) {
            throw new \Exception('convert service max invalid');
        }
        if (ctype_digit($min) === false) {
            throw new \Exception('convert service min invalid');
        }
        if (bccomp($max, $min, 0) <= 0) {
            throw new \Exception('convert service max must greater than min');
        }

        $this->convertSecrets[$convertLength] = [
            'step' => $step,
            'max'  => $max,
            'min'  => $min,
        ];
    }

    /**
     * ID 转 字符串
     * @param int $id
     * @param int $length
     * @return string
     * @throws \Exception
     */
    public function idToCode(int $id, int $length = 6)
    {
        if (!isset($this->convertSecrets[$length])) {
            throw new \Exception('convert service length no set');
        }
        $secret = $this->convertSecrets[$length];
        if (bccomp((string)$id, $secret['max'], 0) >= 0) {
            return '';
        }
        $hash = bcadd(bcmod(bcmul((string)$id, $secret['step']), $secret['max']), $secret['min']);
        $ret = '';
        do {
            $ret = $this->arrMap[bcmod($hash, '62')] . $ret;
            $hash = bcdiv($hash, "62");
        } while ($hash > 0);
        return $ret;
    }

    /**
     * 字符串 转 ID
     * @param string $hash
     * @param int $length
     * @return int
     * @throws \Exception
     */
    public function codeToId(string $hash, int $length = 6)
    {
        if (!isset($this->convertSecrets[$length])) {
            throw new \Exception('convert service length no set');
        }
        $len = strlen($hash);

        if (strlen($hash) != $length) {
            return 0;
        }

        $strDecNum = '0';

        for ($i = 0; $i < $len; $i++) {
            $pos = strpos($this->map, $hash[$i]);
            $strDecNum = bcadd(bcmul(bcpow('62', (string)($len - $i - 1)), (string)$pos), $strDecNum);
        }

        $secret = $this->convertSecrets[$length];
        $strId = bcmod(bcmul(bcsub($strDecNum, $secret['min']), $secret['step']), $secret['max']);
        return $strId < 1 ? 0 : (bccomp($strId, $secret['max'], 0) > 0 ? 0 : intval($strId));
    }

}