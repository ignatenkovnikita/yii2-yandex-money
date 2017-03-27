<?php

namespace ignatenkovnikita\yandexmoney\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * @property \ignatenkovnikita\yandexmoney\YandexMoney $component
 */
class Payment extends Widget
{
    public $componentName = 'yandexMoney';
    public $sum;
    public $customerNumber;
    public $paymentType;
    public $orderNumber;
    public $cps_phone;
    public $cps_email;
    public $data;

    public $submitBtnText;

    public function run()
    {
        echo Html::beginForm($this->component->paymentUrl, 'post');

        echo Html::hiddenInput('shopId', $this->component->settings->SHOP_ID);
        echo Html::hiddenInput('scid', $this->component->settings->SC_ID);
        echo Html::hiddenInput('sum', $this->sum);
        echo Html::hiddenInput('customerNumber', $this->customerNumber);
        echo Html::hiddenInput('orderNumber', $this->orderNumber);
        echo Html::hiddenInput('cps_phone', $this->cps_phone);
        echo Html::hiddenInput('cps_email', $this->cps_email);

        if (is_array($this->paymentType)) {
            echo Html::dropDownList('paymentType', array_keys($this->paymentType)[0], $this->paymentType);
        }

        if (is_array($this->data)) {
            foreach ($this->data as $k => $v) {
                echo Html::hiddenInput($k, $v);
            }
        }

        echo Html::submitButton(($this->submitBtnText != null ? $this->submitBtnText : 'Оплатить'), ['class' => 'btn']);

        echo Html::endForm();
    }

    /**
     * @return \ignatenkovnikita\yandexmoney\YandexMoney;
     */
    public function getComponent()
    {
        return Yii::$app->get($this->componentName);
    }
}
