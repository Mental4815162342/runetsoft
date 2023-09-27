<?php

namespace ExOrder;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use \Bitrix\Main\Service\GeoIp;
use Bitrix\Main\Type\DateTime;

class ExOrder
{
    public static function getCity()
    {
        $ip = GeoIp\Manager::getRealIp();
        $result = GeoIp\Manager::getDataResult($ip, 'ru', ['countryName', 'regionName', 'cityName', 'zipCode']);
        if (empty($result)) {
            $result['STATUS'] = '404';
            $result['MESSAGE'] = 'Неудалось обнаружить местоположение.';
            return $result;
        }
        $result = get_object_vars($result->getGeoData());
        $result['STATUS'] = '200';
        return $result;
    }

    public static function getBasketListUser()
    {
        global $USER;
        return \Bitrix\Sale\Internals\BasketTable::query()
            ->whereNull('ORDER_ID')
            ->where('FUSER_ID', $USER->GetID())
            ->addSelect('*')
            ->fetchAll();
    }

    public static function getSumBasket(): int
    {
        $result = 0;
        foreach (self::getBasketListUser() as $product) {
            $result += $product['PRICE'] * $product['QUANTITY'];
        }
        return $result;
    }

    public static function getLocationEx()
    {
        $locationData = self::getCity();
        if ($locationData['STATUS'] === '200') {
            return $locationData['zipCode'] . ',' . $locationData['countryName'] . ',' . $locationData['regionName']
                . ',' . $locationData['cityName'] . ',';
        } else {
            return $locationData['MESSAGE'];
        }
    }

    public static function createNewOrder(array $data, int $personTypeId, $comment)
    {
        global $USER;

        $name = $data['NAME'];
        $email = $data['EMAIL'];
        $phone = $data['PHONE'];
        $paySystemId = $data['PAYMENT_TYPE'];
        $deliveryId = $data['DELIVERY_TYPE'];

        $emailUser = \CUser::GetList($by="", $order="", array('=EMAIL' => $_POST["EMAIL"]));
        while($arUser = $emailUser->Fetch())
            $userId = $arUser["ID"];
        if (empty($userId)) {
            /** TODO Регистрация пользователя */
            die();
        } else {
            if ($USER->IsAuthorized()) {

            } else {
                $USER->Authorize($userId);
            }
        }
        $sum = self::getSumBasket();
        $fields = [
            'LID' => SITE_ID,
            'PERSON_TYPE_ID' => $personTypeId,
            'STATUS_ID' => 'N',
            'DATE_STATUS' => new DateTime(),
            'EMP_STATUS_ID' => 1,
            'PRICE_DELIVERY' => 0,
            'PRICE_PAYMENT' => 0,
            'PRICE' => $sum,
            'CURRENCY' => 'RUB',
            'DISCOUNT_VALUE' => 0,
            'USER_ID' => $USER->GetID(),
            'PAY_SYSTEM_ID' => $paySystemId,
            'DELIVERY_ID' => $deliveryId,
            'DATE_INSERT' => new DateTime(),
            'DATE_UPDATE' => new DateTime(),
            'USER_DESCRIPTION' => $comment,
            'TAX_VALUE' => 0,
        ];
        $order = \Bitrix\Sale\Internals\OrderTable::add($fields);
        $productListId = \Bitrix\Sale\Internals\BasketTable::query()
            ->addSelect('ID')
            ->where('FUSER_ID', $USER->GetID())
            ->whereNull('ORDER_ID')
            ->fetchAll();
        $productListId = array_column($productListId, 'ID');
        foreach ($productListId as $productId) {
            \Bitrix\Sale\Internals\BasketTable::update($productId, ['ORDER_ID' => $order->getId()]);
        }

        $fields = [
            'ORDER_ID' => $order,
            'ORDER_PROPS_ID' => 7,
            'NAME' => 'Адрес доставки',
            'VALUE' => self::getCity()['STATUS'] == '404' ? self::getCity()['MESSAGE'] : self::getCity()['cityName'],
            'CODE' => 'ADDRESS',
            'ENTITY_ID' => $order,
            'ENTITY_TYPE' => 'ORDER'
        ];
        \Bitrix\Sale\Internals\OrderPropsValueTable::add($fields);
        $fields = [
            'ORDER_ID' => $order,
            'ORDER_PROPS_ID' => 4,
            'NAME' => 'Индекс',
            'VALUE' => self::getCity()['STATUS'] == '404' ? '101000' : self::getCity()['zipCode'],
            'CODE' => 'ZIP',
            'ENTITY_ID' => $order,
            'ENTITY_TYPE' => 'ORDER'
        ];
        \Bitrix\Sale\Internals\OrderPropsValueTable::add($fields);
        $fields = [
            'ORDER_ID' => $order,
            'ORDER_PROPS_ID' => 2,
            'NAME' => 'E-Mail',
            'VALUE' => $email,
            'CODE' => 'EMAIL',
            'ENTITY_ID' => $order,
            'ENTITY_TYPE' => 'ORDER'
        ];
        \Bitrix\Sale\Internals\OrderPropsValueTable::add($fields);
        $fields = [
            'ORDER_ID' => $order,
            'ORDER_PROPS_ID' => 3,
            'NAME' => 'Телефон',
            'VALUE' => $phone,
            'CODE' => 'PHONE',
            'ENTITY_ID' => $order,
            'ENTITY_TYPE' => 'ORDER'
        ];
        \Bitrix\Sale\Internals\OrderPropsValueTable::add($fields);
        $fields = [
            'ORDER_ID' => $order,
            'ORDER_PROPS_ID' => 1,
            'NAME' => 'Ф.И.О.',
            'VALUE' => $name,
            'CODE' => 'FIO',
            'ENTITY_ID' => $order,
            'ENTITY_TYPE' => 'ORDER'
        ];
        \Bitrix\Sale\Internals\OrderPropsValueTable::add($fields);
    }
}
