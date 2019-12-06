<?php

use Snowb\DataWorker\DataWorker;

require dirname(__FILE__) . '/../vendor/autoload.php';



$dataWorker = new DataWorker(dirname(__FILE__). DIRECTORY_SEPARATOR . 'uploads');
echo 1;
if($dataWorker->isPost()) {
    echo 1;
    $dataWorker->uploadFile();
    $dataWorker->convertFile();
    $dataWorker->outputData();
    return true;
}