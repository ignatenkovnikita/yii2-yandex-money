<?php

namespace ignatenkovnikita\yandexmoney;

class Settings
{
    public $SHOP_ID;
    public $SHOP_PASSWORD;
    public $CURRENCY = 10643;

    public $SECURITY_TYPE;
    public $LOG_FILE;


    public $request_source;
    public $mws_cert;
    public $mws_private_key;
    public $mws_cert_password = "123456";

    function __construct($SECURITY_TYPE = "MD5", $request_source = "php://input") {
        $this->SECURITY_TYPE = $SECURITY_TYPE;
        $this->request_source = $request_source;
        $this->LOG_FILE = dirname(__FILE__)."/log.txt";
        $this->mws_cert = dirname(__FILE__)."/mws/shop.cer";
        $this->mws_private_key = dirname(__FILE__)."/mws/private.key";
    }
}
