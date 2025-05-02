<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */

// global $INTRANET_TOOLBAR;
// use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Context,	
	Bitrix\Main\Application,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Engine\Contract\Controllerable,
	Bitrix\Iblock;
use Bitrix\Main\Engine\Contract;

class OtusCurrenciesComponent extends \CBitrixComponent
{

    protected $request; 

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
    */
    public function onPrepareComponentParams($arParams) {
       // тут пишем логику обработки параметров, дополнение к параметрам по умолчанию
       return $arParams;
    }


    private function getCurrencyName($id)
    {
        $element = \Bitrix\Currency\CurrencyLangTable::getList([
            'select' => ['FULL_NAME'],
            'filter' => ['CURRENCY' => $id, 'LID' => LANGUAGE_ID]
        ])->fetch();
        return $element['FULL_NAME'];
    }


    private function getCurrencyAmount($id)
    {
        $element = \Bitrix\Currency\CurrencyTable::getByPrimary($id, [
            'select' => ['AMOUNT_CNT','AMOUNT'],
        ])->fetch();
        return $element;
    }

    public function executeComponent() {

        try
        {

            $this->arResult['NAME'] = $this->getCurrencyName($this->arParams['CURRENCY']);    
            $this->arResult['VALUE'] = $this->getCurrencyAmount($this->arParams['CURRENCY']);    

            // подключаем шаблон
            $this->IncludeComponentTemplate();

        }
        catch (SystemException $e)
        {
            ShowError($e->getMessage());
        }

    }


} 