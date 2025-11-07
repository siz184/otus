<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Компонент Валюты");

?><?$APPLICATION->IncludeComponent(
	"otus:otus.currencies",
	"",
	Array(
		"CURRENCY" => "USD"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>