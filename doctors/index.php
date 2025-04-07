<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
/** @global $APPLICATION */
$APPLICATION->SetTitle('Врачи');
$APPLICATION->SetAdditionalCSS('/doctors/style.css');
use Otus\Models\Lists\DoctorsPropertyValuesTable as DoctorsTable;
?>
<section class="doctors">

<h1>Врачи</h1>

<?php if(!isset($_GET['path'])){ ?>
    
<?
$doctors = DoctorsTable::getList([ 
    'select' => [
        'ID'=>'IBLOCK_ELEMENT_ID',
        'NAME'=>'ELEMENT.NAME',
        'KOD'=>'KOD',
    ], 
    'filter' => [
        'ELEMENT.ACTIVE' => 'Y',
    ],
])
->fetchAll(); 

?>

<div class="cards-list">

<?

foreach ($doctors as $doctor) { ?>
<a href="<?=$doctor['KOD']?>" class="card">
    <?=$doctor['NAME']?>
</a>
<? } ?>

</div>


<? }else{ ?>

    <div class="doctor-page">

    <?

    $kod = $_GET['path'];

$doctor = \Bitrix\Iblock\Elements\ElementDoctorsTable::query() 
->setSelect([
    'ID',
    'NAME',
    'PROC_ID.ELEMENT',
])
->setFilter([
    'ACTIVE' => 'Y',
    'KOD.VALUE' => $kod
    ])
->fetchObject();

    ?>

    <h2><?=$doctor->getName()?></h2>
    <ul>
    <? foreach($doctor->getProcId()->getAll() as $prItem) { ?>
        <li><?=$prItem->getElement()->getName()?></li>
    <? } ?>
    </ul>
    </div>

    <a href="/doctors/">К списку</a>

<? } ?>

</section>

<?

