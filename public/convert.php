<?php

use Snowb\DataWorker\DataWorker;

require dirname(__FILE__) . '/../vendor/autoload.php';



$dataWorker = new DataWorker(dirname(__FILE__). DIRECTORY_SEPARATOR . 'uploads', dirname(__FILE__). DIRECTORY_SEPARATOR . 'downloads');

if($dataWorker->isPost()) {
    $dataWorker->uploadFile();
    $dataWorker->convertFile();
    $dataWorker->outputData();
}

return true;