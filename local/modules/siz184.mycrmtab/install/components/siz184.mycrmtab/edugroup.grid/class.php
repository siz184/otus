<?php

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options as FilterOptions;
use Siz184\Mycrmtab\Orm\EdugroupTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Query\Result;

Loader::includeModule('siz184.mycrmtab');
class EdugroupGrid extends \CBitrixComponent implements Controllerable
{
    public function configureActions(): array
    {
        return [];
    }

    private function getElementActions(): array
    {
        return [];
    }

    private function getHeaders(): array
    {
        return [
            [
                'id' => 'ID',
                'name' => 'ID',
                'sort' => 'ID',
                'default' => true,
            ],
            [
                'id' => 'TITLE',
                'name' => 'Наименование',
                'sort' => 'TITLE',
                'default' => true,
            ],
            [
                'id' => 'YEAR',
                'name' => 'Год',
                'sort' => 'YEAR',
                'default' => true,
            ],
            [
                'id' => 'FACULTY',
                'name' => 'Факультет',
                'sort' => 'FACULTY',
                'default' => true,
            ],
        ];
    }

    public function executeComponent(): void
    {
        $this->prepareGridData();
        $this->includeComponentTemplate();
    }

    private function prepareGridData(): void
    {
        $this->arResult['HEADERS'] = $this->getHeaders();
        $this->arResult['FILTER_ID'] = 'EDUGROUP_GRID';

        $gridOptions = new GridOptions($this->arResult['FILTER_ID']);
        $navParams = $gridOptions->getNavParams();

        $nav = new PageNavigation($this->arResult['FILTER_ID']);
        $nav->allowAllRecords(true)
            ->setPageSize($navParams['nPageSize'])
            ->initFromUri();

        $filterOption = new FilterOptions($this->arResult['FILTER_ID']);
        $filterData = $filterOption->getFilter([]);
        $filter = $this->prepareFilter($filterData);


        $sort = $gridOptions->getSorting([
            'sort' => [
                'ID' => 'DESC',
            ],
            'vars' => [
                'by' => 'by',
                'order' => 'order',
            ],
        ]);

        $edugroupIdsQuery = EdugroupTable::query()
            ->setSelect(['ID'])
            ->setFilter($filter)
            ->setLimit($nav->getLimit())
            ->setOffset($nav->getOffset())
            ->setOrder($sort['sort'])
        ;

        $countQuery = EdugroupTable::query()
            ->setSelect(['ID'])
            ->setFilter($filter)
        ;
        $nav->setRecordCount($countQuery->queryCountTotal());

        $edugroupIds = array_column($edugroupIdsQuery->exec()->fetchAll(), 'ID');

        if (!empty($edugroupIds)) {
            $edugroups = EdugroupTable::getList([
                'filter' => ['ID' => $edugroupIds] + $filter,
                'select' => [
                    'ID',
                    'TITLE',
                    'YEAR',
                    'FACULTY',
                ],
                'order' => $sort['sort'],
            ]);

            $this->arResult['GRID_LIST'] = $this->prepareGridList($edugroups);
        } else {
            $this->arResult['GRID_LIST'] = [];
        }

        $this->arResult['NAV'] = $nav;
        $this->arResult['UI_FILTER'] = $this->getFilterFields();
    }

    private function prepareFilter(array $filterData): array
    {
        $filter = [];

        if (!empty($filterData['FIND'])) {
            $filter['%TITLE'] = $filterData['FIND'];
        }

        if (!empty($filterData['TITLE'])) {
            $filter['%TITLE'] = $filterData['TITLE'];
        }

        if (!empty($filterData['YEAR_from'])) {
            $filter['>=YEAR'] = $filterData['YEAR_from'];
        }

        if (!empty($filterData['YEAR_to'])) {
            $filter['<=YEAR'] = $filterData['YEAR_to'];
        }

        return $filter;
    }

    private function prepareGridList(Result $edugroups): array
    {
        $gridList = [];
        $groupedEdugroups = [];

        while ($edugroup = $edugroups->fetch()) {
            $edugroupId = $edugroup['ID'];

            if (!isset($groupedEdugroups[$edugroupId])) {
                $groupedEdugroups[$edugroupId] = [
                    'ID' => $edugroup['ID'],
                    'TITLE' => $edugroup['TITLE'],
                    'YEAR' => $edugroup['YEAR'],
                    'FACULTY' => $edugroup['FACULTY'],
                ];
            }

        }

        foreach ($groupedEdugroups as $edugroup) {
            $gridList[] = [
                'data' => [
                    'ID' => $edugroup['ID'],
                    'TITLE' => $edugroup['TITLE'],
                    'YEAR' => $edugroup['YEAR'],
                    'FACULTY' => $edugroup['FACULTY'],
                ],
                'actions' => $this->getElementActions(),
            ];
        }

        return $gridList;
    }

    private function getFilterFields(): array
    {
        return [
            [
                'id' => 'TITLE',
                'name' => 'Наименование',
                'type' => 'string',
                'default' => true,
            ],
            [
                'id' => 'YEAR',
                'name' => 'Год',
                'type' => 'number',
                'default' => true,
            ],
        ];
    }
}