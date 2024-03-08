<?php
/**
 * id转code工具快捷操作
 * @param int $id
 * @param int $length
 * @return string
 * @throws Exception
 * @author: RaysonLu
 */
function id_to_code_lj(int $id, int $length = 6)
{
    return app('conv')->idToCode($id, $length);
}

/**
 * code转id工具快捷操作
 * @param string $code
 * @param int $length
 * @return int
 * @throws Exception
 * @author: RaysonLu
 */
function code_lj_to_id(string $code, int $length = 6)
{
    return app('conv')->codeToId($code, $length);
}

/**
 * 生成svg二维码
 * @param string $content
 * @param string $level
 * @param int $margin
 * @return string
 * @author LYF
 */
function getQRCode(string $content = '', string $level = 'L', int $margin = 2)
{
    $qr = app('qrcode')->errorCorrection($level)->margin($margin)->generate($content);
    return 'data:image/svg+xml;base64,' . base64_encode($qr);
}

/**
 * 根据立吉惠项目的role name获取role name在数据对应的值
 * @param string $path
 * @param string $roleName
 * @param null $default
 * @return int|mixed|string|null
 * @author: RaysonLu
 */
function get_lj_role_num(string $path, string $roleName, $default = null)
{
    $flipArrAuthConfig = config('flip_auth_role_name');
    if (!is_array($flipArrAuthConfig) || empty($flipArrAuthConfig)) {
        $flipArrAuthConfig = [];
        $arrAuthConfig = config('auth');
        if (isset($arrAuthConfig['role_name']) && is_array($arrAuthConfig['role_name'])) {
            foreach ($arrAuthConfig['role_name'] as $k => $v) {
                if (is_array($v) && !empty($v)) {
                    $flipArrAuthConfig[$k] = array_flip($v);
                } else {
                    $flipArrAuthConfig[$k] = [];
                }
            }
            config(['flip_auth_role_name' => $flipArrAuthConfig]);
        }
    }
    if (isset($flipArrAuthConfig[$path][$roleName]))
        return $flipArrAuthConfig[$path][$roleName];

    return $default;
}

/**
 * 将包含手机(联系11位数)的名字，隐藏其手机号码
 * @param string $name
 * @return string|string[]|null
 * @author LYF
 */
function hidePhoneNumberName($name)
{
    if (empty($name) || !is_string($name)) {
        return '';
    }
    return preg_replace('/(\d{3})\d{6}(\d{2})/', '$1****$2', $name);
}

/**
 * html过滤
 * @param string $dirty
 * @return string
 * @author LYF
 */
function htmlFilter(string $dirty)
{
    //html代码xxs过滤
    $allowedElements = array('a', 'img', 'br', 'strong', 'b', 'code', 'pre', 'p', 'div', 'em', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'table', 'ul', 'ol', 'tr', 'th', 'td', 'hr', 'li', 'u', 'embed', 'iframe');
    $allowedAttributes = array('title', 'src', 'href', 'id', 'class', 'style', 'width', 'height', 'alt', 'target', 'align');
    $configHtmlPurifier = \HTMLPurifier_Config::createDefault();
    $configHtmlPurifier->set('HTML.AllowedElements', $allowedElements);
    $configHtmlPurifier->set('HTML.AllowedAttributes', $allowedAttributes);
    $configHtmlPurifier->set('Attr.EnableID', true);
    $configHtmlPurifier->set('HTML.MaxImgLength', null);
    $configHtmlPurifier->set('CSS.MaxImgLength', null);
    $configHtmlPurifier->set('HTML.SafeObject', true);
    $configHtmlPurifier->set('HTML.SafeEmbed', true);
    $configHtmlPurifier->set('HTML.FlashAllowFullScreen', true);
    $configHtmlPurifier->set('HTML.SafeIframe', true);
    $configHtmlPurifier->set('URI.SafeIframeRegexp', "%^(http|https)% ");
    $objHtmlPurifier = new \HTMLPurifier($configHtmlPurifier);
    return $objHtmlPurifier->purify($dirty);
}

/**
 * 创建订单号
 * @param string $prefix
 * @return string
 * @author LYF,RaysonLu
 */
function createOrderSn(string $prefix)
{
    /**
     * 格式：
     *      标识  (最多7位)
     *      年月日，不要20开头  (6位)
     *      时间戳     (10位)
     *      毫秒倒数第4、3个数字     (2位)
     *      随机三位数   (3位)
     *      (预留4位给请求微信支付的内部订单号)
     * 注意，提交微信支付的订单号最多只能32位，且只能是数字、大小写字母_-|*@
     */
    $orderSn = substr(date('Ymd'), -6);
    $timeMic = microtime();
    $tmp = explode(' ', $timeMic);
    return $prefix . $orderSn . $tmp[1] . substr($tmp[0], -4, 2) . rand(100, 999);
}

function createSecretOrderSn(string $orderSn)
{
    if (empty($orderSn))
        return false;
    $strs = str_shuffle(md5(microtime(true)));
    $sn = $orderSn . '_' . substr($strs, 0, 3);
    if (strlen($sn) > 32) {
        return false;
    }
    return $sn;
}

function createSubOrderSn(string $prefix, string $orderIdCode)
{
    $timeMic = microtime();
    $tmp = explode(' ', $timeMic);
    return $prefix . time() . $orderIdCode . substr($tmp[0], -4, 2) . rand(100, 999);
}

/**
 * 获取核销码
 * @return int
 * @author LYF
 */
function createVerifyCode()
{
    $min = 11000000;
    $max = 99999999;
    return mt_rand($min, $max);
}

/**
 * 过滤不安全字符，只允许字母，数字和中文，以及不在前头和结尾的小数点
 * 若开启special，则同时允许下划线，以及+-!@#$%^&*字符
 * @param $string
 * @return string
 * @author RaysonLu
 */
function filterSafetyString($string, $special = false, string $diyOtherSpecialRule = '')
{
    $string = trim($string, '.');
    if ($special) {
        $rule = '/[a-zA-Z0-9\x{4e00}-\x{9fa5}';
        if (empty($diyOtherSpecialRule)) {
            $rule .= '_+\-\.!@#$%^&*';
        } else {
            $rule .= $diyOtherSpecialRule;
        }
        $rule .= ']/u';
        preg_match_all($rule, $string, $m);
    } else {
        preg_match_all("/[a-zA-Z0-9\.\x{4e00}-\x{9fa5}]/u", $string, $m);
    }
    return join('', $m[0]);
}


/**
 * 获取今天应该显示上一组的时间区间
 * 根据指定的开始间和每$daysToCounted天统计一次计算。
 * 例如：20220415为开始时间，每3天统计一次，今天是20220419。那么应该返回是的20220415 - 20220417
 * @param int $beginTimestamp
 * @param int $daySToCounted
 * @return array
 */
function getYMDRangeForPrevOne(int $beginTimestamp, int $daySToCounted)
{
    //把时间戳转换成该天的凌晨零点
    $currentDay = strtotime(date('Y-m-d'));
    $beginTimestamp = strtotime(date('Y-m-d', $beginTimestamp));
    //计算"今天"～"$beginTimestamp"，一共多少天
    $days = ($currentDay -  $beginTimestamp)/ 86400 + 1;

    if($days <= 1){
        //开始时间大于或等于当天，直接定位当天是区间1
        $item = 1;
    }else{
        //计算是当天是在第几个区间
        $item = ceil($days / $daySToCounted);
    }

    //计算当天的区间的上一个区间的开始日期和结束日期
    $lastBeginTime = $beginTimestamp + ($item - 2) * $daySToCounted * 86400;
    $lastEndTime = $lastBeginTime + ($daySToCounted-1) * 86400;

    $lastBeginYMD = date('Ymd', $lastBeginTime);
    $lastEndYMD = date('Ymd', $lastEndTime);

    return [$lastBeginYMD, $lastEndYMD];
}


if (!function_exists('get_list_tree')) {
    /**
     * 节点遍历
     * @param array $array
     * @param string $id
     * @param string $parent_id
     * @param string $children
     * @return array
     * @author shao
     */
    function get_list_tree(array $array, string $id = 'id', string $parent_id = 'parent_id', string $children = 'children')
    {
        //第一步 构造数据
        $items = array();
        foreach ($array as $value) {
            $items[$value[$id]] = $value;
        }
        //第二部 遍历数据 生成树状结构
        $tree = array();
        foreach ($items as $key => $item) {
            if (isset($items[$item[$parent_id]])) {
                $items[$item[$parent_id]][$children][] = &$items[$key];
            } else {
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }
}
if (!function_exists('checkDateFormat')) {
    /**
     * 验证日期格式
     * @param $date
     * @return bool
     * @author shao
     */
    function checkDateFormat($date)
    {
        //匹配日期格式
        if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
            //检测是否为日期
            if (checkdate($parts[2], $parts[3], $parts[1]))
                return true;
            else
                return false;
        } else
            return false;
    }
}
if (!function_exists('sprintf2')) {
    /**
     * 保留2为小数
     * @param $val
     * @return float
     * @author shao
     */
    function sprintf2($val)
    {
        return floatval(sprintf("%.2f", substr(sprintf("%.3f", $val), 0, -1)));
    }
}

if (!function_exists('max_min')) {
    /**
     * 获取二维数组某个键的最大值或最小值
     * @param array $arr
     * @param string $keys
     * @return mixed
     * @author shao
     */
    function max_min($arr = [], $keys = '')
    {
        $max['key'] = '';
        $max['value'] = '';
        $min['key'] = '';
        $min['value'] = '';

        foreach ($arr as $key => $val) {

            if ($max['key'] === '') {

                $max['key'] = $key;
                $max['value'] = $val[$keys];

            }

            if ((int)$max['value'] < $val[$keys]) {

                $max['key'] = $key;
                $max['value'] = $val[$keys];

            }

            if ($min['key'] === '') {

                $min['key'] = $key;
                $min['value'] = $val[$keys];

            }

            if ((int)$min['value'] > $val[$keys]) {

                $min['key'] = $key;
                $min['value'] = $val[$keys];
            }

        }
        $array['max'] = $max;
        $array['min'] = $min;
        return $array;
    }
}


if (!function_exists('smallCamelize')) {
    /**
     * 获取小驼峰格式
     * 下划线格式 转换 小驼峰格式
     * @param string $uncamelized_words
     * @return string
     * @author: RaysonLu
     */
    function smallCamelize(string $uncamelized_words)
    {
        //不是下划线格式的字符则原样返回
        if (!preg_match('/_/', $uncamelized_words)) {
            return $uncamelized_words;
        }
        $uncamelized_words = '_' . str_replace('_', " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), '_');
    }
}

if (!function_exists('uncamelize')) {
    /**
     * 获取下划线格式
     * 驼峰格式 转换 下划线格式
     * @param string $camelCaps
     * @return string
     * @author: RaysonLu
     */
    function uncamelize(string $camelCaps)
    {
        //下划线格式的字符则原样返回
        if (!preg_match('/_/', $camelCaps)) {
            return $camelCaps;
        }
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . '_' . "$2", $camelCaps));
    }
}

if (!function_exists('moneyRoundDown')) {

    /**
     * 保留2位小数，并向下取整
     * @param float $money
     * @return int
     * @author Lyf
     */
    function moneyRoundDown(float $money)
    {
        $tmp    = $money / 100;
        $offset = stripos($tmp, '.');
        if ($offset === false) {
            return intval($tmp * 100);
        }
        //截取小数点后2位
        $tmp = substr($tmp, 0, $offset + 3);
        return intval(round($tmp * 100));
    }
}

if (!function_exists('wipeOff')) {
    /**
     * 获取抹零的金额（分）
     * @param float $money
     * @return int
     * @author LYF
     */
    function wipeOff(float $money)
    {
        if ($money <= 100) {
            return 0;
        }
        //转发为元，默认获取小数点后2位
        $tmp = sprintf('%.2f',$money / 100);
        $offset = stripos($tmp, '.');
        //获取小数点后的2位（单位：分）
        return intval(round(substr($tmp, $offset + 1, 2)));
    }
}

if (!function_exists('getPublicWithdrawTax')) {

    /**
     * 对公转账银行手续费
     * @param int $amount
     * @return bool|float|int
     */
    function getPublicWithdrawTax(int $amount)
    {
        //银行手续费规则
        //小于1万(含) -- 3元； 1~10万(含) -- 7元； 10万~50万(含) -- 10元；
        //50万~100万(含) -- 14元；  100万以上，千分之0.02，封顶140元

        //小于等于3元无法进行手续费扣除操作
        if ($amount <= 300)
            return false;

        if ($amount > 10000000) {     //先判断大于或小于10万
            if ($amount <= 50000000)
                return 1000;    //手续费10元
            if ($amount <= 100000000)
                return 1400;    //手续费14元
            //按千分之0.02计算，封顶140元
            $tmp = $amount * 2 / 100 / 1000;
            if ($tmp > 14000)
                return 14000;       //封顶140元
            return $tmp;            //按千分之0.02计算
        } else {
            if ($amount <= 1000000)
                return 300;     //手续费3元
        }

        return 700;
    }
}


