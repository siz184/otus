<?php
namespace Otus\UserTypes;

class Booking
{
    public static function GetUserTypeDescription()
    {
        return array(
            'PROPERTY_TYPE'        => 'S', // тип поля
            'USER_TYPE'            => 'iblock_link', // код типа пользовательского свойства
            'DESCRIPTION'          => 'Запись на процедуру', // название типа пользовательского свойства
            'GetPropertyFieldHtml' => array(self::class, 'GetPropertyFieldHtml'), // метод отображения свойства
            //'GetSearchContent' => array(self::class, 'GetSearchContent'), // метод поиска
            //'GetAdminListViewHTML' => array(self::class, 'GetAdminListViewHTML'),  // метод отображения значения в списке
            //'GetPublicEditHTML' => array(self::class, 'GetPropertyFieldHtml'), // метод отображения значения в форме редактирования
            'GetPublicViewHTML' => array(self::class, 'GetPublicViewHTML'), // метод отображения значения
        );
    }

    public static function getData($id){

        if(empty($id)){
            return [];
        }

        \Bitrix\Main\Loader::includeModule('iblock');

        $data = \Bitrix\Iblock\Elements\ElementdoctorsTable::getList([
            'filter' => ['ID' => $id],
            'select' => ['PROC_ID.ELEMENT'],
        ])->fetchObject();

        $values = [];

        foreach($data->getProcId()->getAll() as $value){
            $values[$value->getElement()->getID()] = $value->getElement()->getName();
        }

        return $values;

    }

    public static function GetPublicViewHTML($arProperty, $arValue, $strHTMLControlName)
    {
        
        $strResult = '';

        $doctor = $arProperty['ELEMENT_ID'];

        $values = self::getData($doctor);
        if(empty($values)){
            return 'Нет процедур';
        }

        $randid = 'rec_id_'.rand(0,99);

        foreach($values as $id => $val){
            $strResult .= '<a style="cursor:pointer" data-proc="'.$id.'" data-doctor="'.$doctor.'" class="booking" id="proc_'.$randid.'_'.$id.'">'.$val.'</a><br />';
        }

        \CJScore::Init(['popup']);

        $strResult .= '
            <script type="text/javascript">

                BX.ready(function(){
                    let book = document.querySelectorAll(".booking");
                    book.forEach(function(proc){
                        proc.addEventListener("click", onAddBook);
                    })
                })

                function onAddBook(e){
                    e.preventDefault();
                    let proc = e.target.getAttribute("data-proc");
                    let doctor = e.target.getAttribute("data-doctor");

                    const currentTime = new Date();

                    let formContent = BX.create("div", {
                        children: [
                            BX.create("input", {
                                attrs: {
                                    type: "text",
                                    name: "name_book",
                                    placeholder: "ФИО",
                                    id: "input_name_book_" + proc,
                                    required: true
                                }
                            }),
                            BX.create("br"),            
                            BX.create("br"),
                            BX.create("input", {
                                attrs: {
                                    type: "datetime-local",
                                    name: "date_book",
                                    id: "input_date_book_" + proc,
                                    required: true,
                                    min: currentTime
                                }
                            }),
                            BX.create("br"),
                            BX.create("p",{
                                attrs: {
                                    id: "popupError_"+proc    
                                }
                            }),
                        ]
                    })  
                    
                    BX.PopupWindowManager.create("bookPopup_"+proc,proc,{
                        content: formContent,
                        titleBar: {content: BX.create("h2",{html:"Запись"})},
                        closeIcon: {right: "20px", top: "10px"},
                        width: 400,
                        height: 400,
                        zIndex: 100,
                        buttons: [
                            new BX.PopupWindowButton({
                                text: "Записать",
                                id: "add_new_book_"+proc,
                                events: {
                                    click: function(){
                                        let nameVal = BX("input_name_book_"+proc).value;
                                        let dateVal = BX("input_date_book_"+proc).value;
                                        BX.ajax({
                                            url: "/local/ajax/addBooking.php",
                                            method: "POST",
                                            data: {
                                                NAME: nameVal,
                                                DATE: dateVal,
                                                PROC: proc,
                                                DOCTOR: doctor
                                            },
                                            dataType: "json",
                                            processData: true,
                                            onsuccess: function(data){
                                                if(data.success){

                                                    BX("popupError_"+proc).innerHTML = data.success;
                                                    setTimeout(function(){
                                                        BX.PopupWindowManager.getCurrentPopup().destroy();
                                                        BX.PopupWindowManager.getCurrentPopup().close();
                                                    },1000)

                                                }else{

                                                    BX("popupError_"+proc).innerHTML = data.error;

                                                }
                                                
                                            },
                                            onfailure: function(){

                                                BX("popupError_"+proc).innerHTML = data.error;

                                            }
                                        });
                                    }
                                }
                            })
                        ]
                    }).show();   
                }     

            </script>        
        ';

        return $strResult;
    }


    public static function GetPropertyFieldHtml($arProperty, $arValue, $strHTMLControlName)
    {
        $strResult = '<button id="booking-but">Записаться</button>';
        return $strResult;
    }

}

