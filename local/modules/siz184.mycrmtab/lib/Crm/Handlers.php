<?php
namespace Siz184\Mycrmtab\Crm;

use Siz184\Mycrmtab\Orm\EdugroupTable;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
class Handlers
{
    public static function updateTabs(Event $event): EventResult
    {
        $availableEntityIds = Option::get('siz184.mycrmtab', 'ENTITIES_TO_DISPLAY_TAB');
        $availableEntityIds = explode(',', $availableEntityIds);
        $entityTypeId = $event->getParameter('entityTypeID');
        $entityId = $event->getParameter('entityID');
        $tabs = $event->getParameter('tabs');
        if (in_array($entityTypeId, $availableEntityIds)) {
            $tabs[] = [
                'id' => 'edugroup_tab_' . $entityTypeId . '_' . $entityId,
                'name' => 'Учебные группы',
                'enabled' => true,
                'loader' => [
                    'serviceUrl' => sprintf(
                        '/bitrix/components/siz184.mycrmtab/edugroup.grid/lazyload.ajax.php?site=%s&%s',
                        \SITE_ID,
                        \bitrix_sessid_get(),
                    ),
                    'componentData' => [
                        'template' => '',
                        'params' => [
                            'ORM' => EdugroupTable::class,
                            'DEAL_ID' => $entityId,
                        ],
                    ],
                ],
            ];
        }
        

        return new EventResult(EventResult::SUCCESS, ['tabs' => $tabs,]);
    }
}