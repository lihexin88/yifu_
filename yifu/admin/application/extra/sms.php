<?php
define('APP_KEY', '');
define('SECRET_KEY', '');

function msg_code($code, $phone)
{
    $phone = trim($phone);
    vendor('alidayu.s');
    $c = new \TopClient();
    $c->appkey = APP_KEY;
    $c->secretKey = SECRET_KEY;
    $req = new  \AlibabaAliqinFcSmsNumSendRequest();
    $req->setExtend("");
    $req->setSmsType("normal");
    $req->setSmsFreeSignName("盈股在线");
    $req->setSmsParam("{code:'$code'}");
    $req->setRecNum("$phone");
    $req->setSmsTemplateCode("SMS_118560025");
    $resp = $c->execute($req);
    return $resp;
}
