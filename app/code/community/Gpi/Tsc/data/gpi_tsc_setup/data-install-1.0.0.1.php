<?php
 
$installer = $this;

$dataRows = array(
    array(
        'config_name' => 'serviceUrl',
        'config_value' => '',
    ),
    array(
        'config_name' => 'authToken',
        'config_value' => '',
    ),
);


foreach ($dataRows as $data) {
    $model = Mage::getModel('gpi_tsc/configurations');
    $model->setData($data)->setOrigData()->save();
}