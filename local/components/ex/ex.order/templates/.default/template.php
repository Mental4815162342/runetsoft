<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die(); ?>
<?
global $APPLICATION;
?>

<table class="ex-table">
    <tbody>
    <tr>
        <td>Товар</td>
        <td>Кол-во</td>
        <td>Цена</td>
        <td>Сумма</td>
    </tr>
    <?foreach ($arResult['BASKET_LIST'] as $product):?>
    <tr>
        <td><?=$product['NAME']?></td>
        <td><?=(int) $product['QUANTITY']?></td>
        <td><?=(int) $product['PRICE']?></td>
        <td><?=$product['PRICE'] * $product['QUANTITY']?></td>
    </tr>
    <?endforeach;?>
    </tbody>
</table>
<p class="location">Итого: <span><?=$arResult['SUM']?></span></p>
<p class="location">Ваше местоположение: <span><?=$arResult['LOCATION_STRING']?></span></p>
<form method="post" action="<?=$APPLICATION->GetCurPage()?>">
    <input name="NAME" value="<?=$arParams['NAME']?>">
    <input name="EMAIL" value="<?=$arParams['EMAIL']?>">
    <input name="PHONE" value="<?=$arParams['PHONE']?>">
    <input name="PAYMENT_TYPE" value="<?=$arParams['PAYMENT_TYPE']?>">
    <input name="DELIVERY_TYPE" value="<?=$arParams['DELIVERY_TYPE']?>">
    <input name="COMMENT" value="<?=$arParams['COMMENT']?>">
    <input name="ADDRESS" value="<?=$arParams['ADDRESS']?>">
    <button type="submit">Оформить заказ!</button>
</form>
