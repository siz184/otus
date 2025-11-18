<?php

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
    'main',
    'OnEpilog',
    function(){
        Bitrix\Main\UI\Extension::load("extensions.workday");
    }
);