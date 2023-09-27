<?php

use \ExOrder\ExOrder;

$arResult['BASKET_LIST'] = ExOrder::getBasketListUser();
$arResult['LOCATION'] = ExOrder::getCity();
$arResult['LOCATION_STRING'] = ExOrder::getLocationEx();
$arResult['SUM'] = ExOrder::getSumBasket();

if (!empty($_POST)) {
    ExOrder::createNewOrder($_POST, 1, $arParams['COMMENT']); // 1 - физическое лицо
}

$this->IncludeComponentTemplate();
