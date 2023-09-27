<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
\Bitrix\Main\Loader::includeModule('sale');
$arPaymentType = \Bitrix\Sale\Internals\PaySystemActionTable::query()
    ->addSelect('*')
    ->fetchAll();
foreach ($arPaymentType as $value) {
    $arPaymentTypeValues[$value['ID']] = '[' . $value['ID'] . '] ' . $value['NAME'];
}
$arDeliveryType = \Bitrix\Sale\Delivery\Services\Table::query()
    ->addSelect('*')
    ->fetchAll();
foreach ($arDeliveryType as $value) {
    $arDeliveryTypeValues[$value['ID']] = '[' . $value['ID'] . '] ' . $value['NAME'];
}
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "NAME" => array(
            "PARENT" => "BASE",
            "NAME" => "Имя пользователя",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
        "EMAIL" => array(
            "PARENT" => "BASE",
            "NAME" => "E-mail",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
        "PHONE" => array(
            "PARENT" => "BASE",
            "NAME" => "Телефон",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
        "PAYMENT_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => "Способ оплты",
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            "VALUES" => $arPaymentTypeValues,
        ),
        "DELIVERY_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => "Метод доставки",
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            "VALUES" => $arDeliveryTypeValues,
        ),
        "COMMENT" => array(
            "PARENT" => "BASE",
            "NAME" => "Комментарий",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
        "ADDRESS" => array(
            "PARENT" => "BASE",
            "NAME" => "Адрес доставки",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
    )
);