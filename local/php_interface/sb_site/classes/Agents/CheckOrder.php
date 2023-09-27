<?php

namespace SB\Site\Agents;

use Bitrix\Main\Loader;

class CheckOrder {
    public static function getOrder(): array
    {

        $dateFilter = (new \DateTime())
            ->sub(new \DateInterval('P2D'));
        $dateFilter = \Bitrix\Main\Type\DateTime::createFromPhp($dateFilter);

        Loader::includeModule('Sale');

        $orders = \Bitrix\Sale\Internals\OrderTable::query()
            ->where('STATUS_ID', 'N')
            ->where('DATE_STATUS', '<=', $dateFilter)
            ->addSelect('*')
            ->fetchAll();
        $result = [];
        foreach ($orders as $key => $order) {
            $result[$key]['ID'] = $order['ID'];
            $result[$key]['USER_ID'] = $order['USER_ID'];
            $rsUser = \CUser::GetByID($order['USER_ID']);
            $result[$key]['USER_LOGIN'] = $rsUser->Fetch()['LOGIN'];
            $result[$key]['PAYED'] = $order['PAYED'] === 'N' ? 'Не оплачен' : 'Оплачен';
            $result[$key]['PRICE'] = $order['PRICE'];
        }
        return $result;
    }

    public static function prepareEmailToAdmin(): string
    {
        $data = self::getOrder();
        $table = '';
        foreach ($data as $el) {
            $table .= '| [<a href="' . SITE_SERVER_NAME . '/bitrix/admin/sale_order_view.php?ID=' . $el['ID'] . '">'
                . $el['ID'] . '</a>] | [' . $el['USER_ID'] . '] ' . $el['USER_LOGIN'] . ' | ' . $el['PRICE']  . ' | '
                . $el['PAYED']  . ' |<br/>';
        }
        return $table;
    }
    public static function sendEmail()
    {
        $eventFields = [
            'TEXT' => self::prepareEmailToAdmin(),
        ];
        \CEvent::Send('CHECK_ORDER_STATUS', SITE_ID, $eventFields);
    }
}