<?php
namespace Siz184\Mycrmtab\Orm;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\DateField;

class EdugroupTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'siz184_edugroup';
    }

    public static function getMap(): array
    {
        return [
            (new IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete()
                ->configureTitle('ID'),

            (new StringField('TITLE'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle('Наименование'),

            (new IntegerField('YEAR'))
                ->configureTitle('Год'),

            (new StringField('FACULTY'))
                ->configureSize(255)
                ->configureTitle('Факультет'),

        ];
    }
}