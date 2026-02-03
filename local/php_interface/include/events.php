<?php define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");
\Bitrix\Main\Loader::includeModule("crm");
\Bitrix\Main\Loader::includeModule('iblock');
use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

// пользовательский тип для свойства инфоблока
$eventManager->AddEventHandler(
    'iblock',
    'OnIBlockPropertyBuildList',
    [
        'Otus\UserTypes\Booking', // класс обработчик пользовательского типа свойства 
        'GetUserTypeDescription'
    ]
);


$eventManager->AddEventHandler(
    "iblock", 
    "OnAfterIBlockElementUpdate",
    "onAfterZayavkaUpdate"
);

function onAfterZayavkaUpdate( &$arFields ){
    if($arFields['IBLOCK_ID']=='26'){

        $element = \Bitrix\Iblock\Elements\ElementZayavkaTable::getByPrimary($arFields['ID'], array(
            'select' => array('ID', 'SDELKA', 'SUMMA')
        ))->fetchObject();

        $summa = explode('|',$element->getSumma()->getValue());
        $number = (double) $summa[0];
        $currency = $summa[1];
        
        $sdelka = $element->getSdelka()->getValue();

        if($sdelka){
        
            $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
            $item = $factory->getItem($sdelka);
            if($number != $item->getOpportunity() || $currency != $item->getCurrencyId()){
                $item->setOpportunity($number);
                $item->setCurrencyId($currency);
                $item->save();
            }
            // AddMessage2Log($number);

        }
    }
}
    
$eventManager->addEventHandler(
    "crm", 
    "OnAfterCrmDealUpdate",
    "onAfterSdelkaUpdate"
);

function onAfterSdelkaUpdate( &$arFields ){

    $element = \Bitrix\Iblock\Elements\ElementZayavkaTable::getList([
        'select' => ['ID', 'NAME', 'SUMMA', 'SDELKA'],
        'filter' => [
            'SDELKA.VALUE' => $arFields['ID'],
        ],
    ])->fetchObject();

    $summa = explode('|',$element->getSumma()->getValue());
    $number = (double) $summa[0];
    $currency = $summa[1];
    
    if($number != $arFields['OPPORTUNITY'] || $currency != $arFields['CURRENCY_ID']){

        // AddMessage2Log($element->getSdelka()->getValue());
        CIBlockElement::SetPropertyValuesEx($element->getId(),'',['SUMMA' => $arFields['OPPORTUNITY'].'|'.$arFields['CURRENCY_ID']]);

    }

    
}

$eventManager->AddEventHandler(
    'main',
    'OnEpilog',
    function(){
        Bitrix\Main\UI\Extension::load("extensions.workday");
    }
);

$eventManager->AddEventHandler(
    "main", 
    "OnEpilog", 
    function(){
        Bitrix\Main\UI\Extension::load("extensions.hidecounter");
    }
);

$eventManager->addEventHandlerCompatible("mobile", "onMobileMenuStructureBuilt", "onMobileMenuStructureBuilt");

function onMobileMenuStructureBuilt( $menu )
{
    $menu[] = array(
   'title' => 'Другое',
   'min_api_version' => 22,
   'hidden' => false,
   'sort' => 22,
   'items' =>
    array(
     array(
      'title' => 'Компания',
      'color' => '#8bd100',
      'unselectable' => true,
      'imageUrl' => '/bitrix/components/bitrix/mobile.jscomponent/jscomponents/more/images/crm/icon-crm-company.png',
        'attrs' =>
        array (
            'url' => '/mobile/news/',
            'id' => 'company_about',
        ),
     ),
    )
  );
  return $menu;
}