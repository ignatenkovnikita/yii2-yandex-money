<?php

namespace ignatenkovnikita\yandexmoney;

use Yii;

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

    public function listOrders($orderNumber = null, $format = 'XML')
    {
        $mws = new mws\MWS($this->settings);
        $ordersInfo = $mws->listOrders($orderNumber, $format);
        $result = false;

        switch ($format) {
            case 'XML':
                try {
                    $ordersInfo = new \SimpleXMLElement($ordersInfo);
                    $error = (int) $ordersInfo->attributes()->error;
                    $status = (int) $ordersInfo->attributes()->status;
                    $orders = [];

                    if ($error === 0 && $status === 0) {
                        foreach ($ordersInfo->children() as $orderInfo) {
                            $orderInfo = (array) $orderInfo->attributes();
                            $orders[] = $orderInfo['@attributes'];
                        }

                        $result = $orderNumber != null ? $orders[0] : $orders;
                    }
                } catch (\Exception $e) {}
                break;
        }

        return $result;
    }

    public function paymentAviso($request)
    {
        $yaMoneyCommonHttpProtocol = new YaMoneyCommonHttpProtocol('paymentAviso', $this->settings);
        $yaMoneyCommonHttpProtocol->processRequest($request);
    }

    public function checkMD5($request)
    {
        $yaMoneyCommonHttpProtocol = new YaMoneyCommonHttpProtocol('paymentAviso', $this->settings);

        return $yaMoneyCommonHttpProtocol->checkMD5($request);
    }
}
