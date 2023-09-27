<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("");
global $APPLICATION;
?><style>
    .fb__spinner-wrapper {
        display: none!important;
    }
</style>
<?$APPLICATION->IncludeComponent(
	"ex:ex.order",
	".default",
	Array(
		"ADDRESS" => "address",
		"COMMENT" => "comment",
		"COMPONENT_TEMPLATE" => ".default",
		"DELIVERY_TYPE" => "2",
		"EMAIL" => "bamail1999@gmail.com",
		"NAME" => "user",
		"PAYMENT_TYPE" => "2",
		"PHONE" => "+79999999999"
	)
);?><?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>