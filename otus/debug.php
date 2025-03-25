<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php';
$date = date('d.m.Y H:i:s');
file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/logs/task_1.log',$date . PHP_EOL, FILE_APPEND);