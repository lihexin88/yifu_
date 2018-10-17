<?php
/**
 * 手机号码检查
 * @param $phone
 * @return int
 */
function reg_phone($phone)
{
    return preg_match(REG_PHONE, $phone);
}

/**
 * 密码检查
 * @param $password
 * @return int
 */
function reg_password($password)
{
    return preg_match(REG_PASSWORD, $password);
}

/**
 * 整数判断
 * @param $number
 * @return int
 */
function reg_number($number)
{
    return preg_match(POSITIVE_INTEGER, $number);
}

/**
 * 身份证号码检查
 * @param $card
 * @return int
 */
function reg_card($card)
{
    return preg_match(REG_CARD, $card);
}

/**
 * 中文检查
 * @param $name
 * @return int
 */
function chinese_name($name)
{
    return preg_match(CHINESE_NAME, $name);
}
