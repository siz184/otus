<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Currency;

if (!Loader::includeModule('currency'))
{
	return;
}

$arComponentParameters = array(
	"GROUPS" => array(
		"LIST"=>array(
			"NAME"=>GetMessage("CHOOSE_CURRENCY_PARAMETERS"),
			"SORT"=>"300"
		)
	),
	"PARAMETERS" => array(
		"CURRENCY" =>  array(
			"PARENT" => "LIST",
			"NAME"=>GetMessage("CHOOSE_CURRENCY_TEXT"),
			"TYPE"=>"LIST",
			'VALUES' => Currency\CurrencyManager::getCurrencyList(),
			"DEFAULT"=>"USD"
		),
	)
);


