<?php

namespace ignatenkovnikita\yandexmoney;

class YandexMoney extends  \yii\base\Component
{
    public $shopPath;
    public $paymentUrl = YII_ENV_DEV ? 'https://demomoney.yandex.ru/eshop.xml' : 'https://money.yandex.ru/eshop.xml';
    public $infoUrl = YII_ENV_DEV ? 'https://penelope-demo.yamoney.ru:8083' : 'https://penelope.yamoney.ru';
    public $settings;

    public function init()
    {
        parent::init();

        $fileSettings = $this->parseSettingsFile();

        $settings = new \stdClass();
        $settings->SHOP_ID = $fileSettings['SHOP_ID'];
        $settings->SC_ID = $fileSettings['SC_ID'];
        $settings->SHOP_PASSWORD = $fileSettings['SHOP_PASSWORD'];
        $settings->CURRENCY = $fileSettings['CURRENCY'];
        $settings->AGENT_ID = $fileSettings['AGENT_ID'];
        $settings->SECURITY_TYPE = $fileSettings['SECURITY_TYPE'];
        $settings->mws_cert = $this->shopPath . '/mws/shop.cer';
        $settings->mws_private_key = $this->shopPath . '/mws/private.key';
        $settings->mws_cert_password = $fileSettings['MWS_CERT_PASSWORD'];
        $settings->request_source = 'php://input';

        $this->settings = $settings;
    }

    public function parseSettingsFile()
    {
        $filePath = $this->shopPath . '/settings';

        if (is_file($filePath)) {
            $fileSettings = [];
            $fileSettingsTemp = explode("\n", file_get_contents($filePath));

            foreach ($fileSettingsTemp as $k => $v) {
                $kv = explode('=', $v);
                $fileSettings[trim($kv[0])] = trim($kv[1]);
            }

            return $fileSettings;
        }

        return [];
    }

    public function listOrders()
    {
        //$mws = new mws\MWS($this->settings);
        //$result = $mws->listOrders(777);
        //$paymentResult = $mws->confirmPayment(777, 9999);
    }
}
