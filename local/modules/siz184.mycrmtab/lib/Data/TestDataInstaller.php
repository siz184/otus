<?php
namespace Siz184\Mycrmtab\Data;

use Siz184\Mycrmtab\Orm\EdugroupTable;
use Bitrix\Main\SystemException;

class TestDataInstaller
{

    public static function addEdugroups(): void
    {
        $edugroups = [
            [
                'TITLE' => 'ФИС/2025',
                'YEAR' => 2025,
                'FACULTY' => 'ФИС',
            ],
            [
                'TITLE' => 'ФБК/2024',
                'YEAR' => 2024,
                'FACULTY' => 'ФБК',
            ],
            [
                'TITLE' => 'ФМТ/2023',
                'YEAR' => 2023,
                'FACULTY' => 'ФМТ',
            ],
            [
                'TITLE' => 'ФМТ/2025',
                'YEAR' => 2025,
                'FACULTY' => 'ФМТ',
            ],
        ];

        foreach ($edugroups as $edugroupData) {

            $resultAdd = EdugroupTable::add($edugroupData);
            if (!$resultAdd->isSuccess()) {
                throw new SystemException('Не удалось добавить тестовые данные: ' . implode(', ', $resultAdd->getErrorMessages()));
            }

        }
    }
}