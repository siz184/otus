<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$req = ['error'=>'Что-то пошло не так!'];

$request = Bitrix\Main\Context::getCurrent()->getRequest();
if ($request->isPost()){
    $data = $request->getPostList()->toArray();

    if(!empty($data['PROC']) && !empty($data['DOCTOR']) && !empty($data['DATE'])){

        \Bitrix\Main\Loader::includeModule("iblock");

        if(strtotime($data['DATE']) < time()){
            
            $req = ['error'=>'Дата не может быть меньшн текущей!'];

        }else{

            $min = date('Y-m-d H:i:s',strtotime($data['DATE']. '-1 hour'));
            $max = date('Y-m-d H:i:s',strtotime($data['DATE']. '+1 hour'));

            $exist = \Bitrix\Iblock\Elements\ElementbookingTable::getList([
                    'filter' => ['DOCTOR.VALUE' => $data['DOCTOR'], '><DATE.VALUE' => [$min, $max]],
                    'select' => ['ID'],
                ])->fetchObject();

            if(!empty($exist)){

                $req = ['error'=>'Уже есть запись на это время!'];
                
            }else{

                $el = new CIBlockElement();
                $newDate = str_replace('T',' ',$data['DATE']) . ":00";
                $elArr = [
                    'IBLOCK_ID' => 20,
                    'PROPERTY_VALUES' => [
                        'PROC' => $data['PROC'],
                        'DATE' => $newDate,
                        'DOCTOR' => $data['DOCTOR']
                    ],
                    'NAME' => $data['NAME'],
                    'ACTIVE' => 'Y'
                ];
                if($el->Add($elArr)){
                    $req = ['success'=>'Запись добавлена!'];
                }

            }

        }

    }else{
        $req = ['error'=>'Что-то пошло не так!!'];
    }   

}else{
    $req = ['error'=>'Что-то пошло не так!!!'];
}

echo json_encode($req, JSON_UNESCAPED_UNICODE);