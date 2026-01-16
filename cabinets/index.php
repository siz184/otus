<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
/** @global $APPLICATION */
$APPLICATION->SetTitle('Кабинеты');
$APPLICATION->SetAdditionalCSS('/cabinets/style.css');
use Otus\Models\CabTable as Cabs;
?>
<section class="doctors">

<h1>Кабинеты</h1>
    
<?

$cabs = Cabs::getList([ 
    'select' => [
        'id',
        'name',
        'DEP',
        'PROC',
        'PROC.ELEMENT.NAME',
    ], 
])
->fetchCollection(); 

?>

<div class="cards-list">

<?

foreach ($cabs as $key => $cab) { ?>
<a href="#" class="card">
    <?=$cab->getName()?><br />
    <i><?=$cab->getDep()->getFullTitle()?></i>
    <ul>
    <? foreach($cab->getProc()->getAll() as $item) { ?>
        <li>
            <? echo $item->getElement()->getName(); ?> 
            (<? echo $item->getDuration(); ?>)
        </li> 
    <? } ?>

    </ul>
</a>
<? } ?>

</div>

</section>

<?

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");