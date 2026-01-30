<?php
namespace Otus\Models;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\Entity\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;

use Otus\Models\Lists\DoctorsProcPropertyValuesTable as ProcTable;
use Otus\Models\Lists\DepartmentsPropertyValuesTable as DepartmentsTable;

//use \Bitrix\Iblock\Elements\ElementDepartmentTable as Department;
//use Lists\ProcTable as Proc;

/**
 * Class CabTable
 **/

class CabTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'otus_cab';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			'id' => (new IntegerField('id',
					[]
				))->configureTitle(Loc::getMessage('CAB_ENTITY_ID_FIELD'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
			'name' => (new StringField('name',
					[
						'validation' => function()
						{
							return[
								new LengthValidator(null, 255),
							];
						},
					]
				))->configureTitle(Loc::getMessage('CAB_ENTITY_NAME_FIELD'))
						->configureRequired(true)
			,
			'sort' => (new IntegerField('sort',
					[]
				))->configureTitle(Loc::getMessage('CAB_ENTITY_SORT_FIELD'))
			,
			'department_id' => (new IntegerField('department_id',
					[]
				))->configureTitle(Loc::getMessage('CAB_ENTITY_DEPARTMENT_ID_FIELD'))
			,

            (new Reference('DEP', DepartmentsTable::class, Join::on('this.department_id', 'ref.IBLOCK_ELEMENT_ID')))
            ->configureJoinType('inner'),

            (new ManyToMany('PROC', ProcTable::class))
            ->configureTableName('otus_cab_proc')
            ->configureLocalPrimary('id', 'cab_id')
            ->configureLocalReference('CAB')
            ->configureRemotePrimary('IBLOCK_ELEMENT_ID', 'proc_id')
            ->configureRemoteReference('PROC'),
		];
	}
}