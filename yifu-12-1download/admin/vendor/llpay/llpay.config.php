<?php

/* *
 * 配置文件
 * 版本：1.0
 * 日期：2016-11-28
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//商户编号是商户在连连钱包支付平台上开设的商户号码，为18位数字，如：201306081000001016
$llpay_config['oid_partner'] = '201807270002082004';

//秘钥格式注意不能修改（左对齐，右边有回车符）  商户私钥，通过openssl工具生成,私钥需要商户自己生成替换，对应的公钥通过商户站上传
/*$llpay_config['RSA_PRIVATE_KEY'] ='-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDFy1PWWxOSAG4axCTXQgJDp8kvZampWitooAVygU31QraujBlJqsZOWT9OszcqUnYyXZa7oD48mGP2VnAsNTuen0C8E5Ps16VSWaksMU5bv9rN8eS9HEevurrbjOha
-----END RSA PRIVATE KEY-----';*/
$llpay_config['RSA_PRIVATE_KEY'] = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDFy1PWWxOSAG4axCTXQgJDp8kvZampWitooAVygU31QraujBlJ
qsZOWT9OszcqUnYyXZa7oD48mGP2VnAsNTuen0C8E5Ps16VSWaksMU5bv9rN8eS9
HEevurrbjOha/EAtNCvYwizg6J2k0l/wYkkhvFYZyX3GBUewQ3YqVu54/QIDAQAB
AoGAfZWS3pgnPd1OC2qcZZxAqco/n/txlNhulVh8/O4CnIXGTd8gMzjd/mMGIicC
ELoY1/O6P9kHixvK5F6PjFIkTn2w07PR1d0EjQyZ4z7AvxDITGzukgfBqeDEsj1Z
lspv7LKLlbhLhfR1a1OPKT9n+A4f5hWOiiIZGuoQTIdEOrUCQQDonoOjhO0JfBj8
i2oq3aBypDh49AXQCj5Jp8g4fR+Lt/aZQMiJXV3HQx6ieL+RweXqd0RC4fKyEtwV
sHHtTVi7AkEA2ay9A5qdDpQQJ71u/alD+ExMyFQcivngJF8LBiObcEs84jh/7bJX
0AgBekyDRO7dbo/FR/mqzXY4fu/wfJLVpwJAGz++UeBXV/4Wezbll+HgUq2UA+8p
4yXgCAbEeIGHuXkyRN+G3jh2rMnbA9M7NZrOEPopn+AR6vQ0ncHOhqrB0wJBAJYs
hGKeIsjSAatJF+/M/WWqTjbPQLno1miBYbukiTKZ1bFExY2Zwtd1Dn/vlYjTOtVy
Pur/JoWFkEiTBNkM/HUCQDHcjjU/x67smJ5sIdblbdPm90dfyQf/BcGi5tXpODvx
T6EnDF1q9TS2H71FO3Zw23JLvAV/4crJJO1A7Qs7OJ4=
-----END RSA PRIVATE KEY-----';

//连连银通公钥
$llpay_config['LIANLIAN_PUBLICK_KEY'] ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCSS/DiwdCf/aZsxxcacDnooGph3d2JOj5GXWi+
q3gznZauZjkNP8SKl3J2liP0O6rU/Y/29+IUe+GTMhMOFJuZm1htAtKiu5ekW0GlBMWxf4FPkYlQ
kPE0FtaoMP3gYfh+OwI+fIRrpW3ySn3mScnc6Z700nU/VYrRkfcSCbSnRwIDAQAB
-----END PUBLIC KEY-----';	

//安全检验码，以数字和字母组成的字符
$llpay_config['key'] = '201408071000001539_sahdisa_20141205';

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

//签名方式 不需修改
$llpay_config['sign_type'] = strtoupper('RSA');


//字符编码格式 目前支持 gbk 或 utf-8
$llpay_config['input_charset'] = strtolower('utf-8');


?>