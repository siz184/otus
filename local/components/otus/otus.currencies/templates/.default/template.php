<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

?>

<h2>Курс валюты</h2>

<table border="1">
<tr>
<td><?=$arResult['VALUE']['AMOUNT_CNT']?> <?=$arResult['NAME']?></td>
<td><?=$arResult['VALUE']['AMOUNT']?> RUB</td>
</tr>
</table>
