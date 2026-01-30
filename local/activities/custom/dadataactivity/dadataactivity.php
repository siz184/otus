<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Bizproc\Activity\BaseActivity;
use Bitrix\Bizproc\FieldType;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\Activity\PropertiesDialog;
use \Bitrix\Crm;
\Bitrix\Main\Loader::includeModule('crm');

class CBPDadataActivity extends BaseActivity
{
    /**
     * @see parent::_construct()
     * @param $name string Activity name
     */
    public function __construct($name)
    {
        parent::__construct($name);

        $this->arProperties = [
            'Inn' => '',
            'Text' => null,
        ];

        $this->SetPropertiesTypes([
            'Text' => ['Type' => FieldType::STRING],
        ]);
    }

    /**
     * Return activity file path
     * @return string
     */
    protected static function getFileName(): string
    {
        return __FILE__;
    }

    /**
     * @return ErrorCollection
     */
    protected function internalExecute(): ErrorCollection 
    {
        $errors = parent::internalExecute(); 

        $companyID = 0;

        $this->preparedProperties['Text'] = $this->Inn;
        $this->log($this->preparedProperties['Text']);

        $existsCompanies = Crm\CompanyTable::getList([
            'select' => [
                'ID',
            ],
            'filter' => [
                'UF_INN' =>  $this->Inn
            ]
        ]);

        foreach ($existsCompanies as $existsCompany){

            $companyID = $existsCompany['ID'];

        }

        if($companyID==0){

            $token = "6bd80515c368c952dd190826ebd0b51d553e61c1";
            $dadata = new \Dadata\DadataClient($token, null);
            $response = $dadata->findById("party", $this->Inn, 1);

            $this->preparedProperties['Text'] = $response[0]['value'];
            $this->log($this->preparedProperties['Text']);

            if(!empty($response)){ 
                foreach($response as $company){
                    $companyID = self::addCompany($company);
                }
            }  

        }

        $rootActivity = $this->GetRootActivity();
        $rootActivity->SetVariable("companyId", $companyID); 

        return $errors;
    }

    /**
     * @param PropertiesDialog|null $dialog
     * @return array[]
     */
    public static function getPropertiesDialogMap(?PropertiesDialog $dialog = null): array
    {
        $map = [
            'Inn' => [
                'Name' => 'ИНН',
                'FieldName' => 'inn',
                'Type' => FieldType::STRING,
                'Required' => false,
                'Options' => [],
            ],
        ];
        return $map;
    }

    public static function addCompany($fields){

        $responsible = 1;

            // создаем компанию
            $arNewCompany = array(
                "TITLE" => $fields['value'],
                "OPENED" => "Y",
                "COMPANY_TYPE" => "CUSTOMER",
                "ASSIGNED_BY_ID" => $responsible,
                "ADDRESS" => $fields['data']['address']['value'],
                "UF_INN" => $fields['data']['inn'],
            );

            $company = new CCrmCompany(false);
            return $company->Add($arNewCompany)?:0;

    }

}