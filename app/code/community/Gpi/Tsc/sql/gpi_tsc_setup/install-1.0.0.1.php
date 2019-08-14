<?php
 
$installer = $this;
 
$installer->startSetup();
 

$table = $installer->getConnection()
    ->newTable($installer->getTable('gpi_tsc/backgroundjobs'))
    ->addColumn('job_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Id')
    ->addColumn('job_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
        ), 'JobType')
	->addColumn('job_params', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable'  => false,
        ), 'JobParams')
    ->addIndex($installer->getIdxName(
            $installer->getTable('gpi_tsc/backgroundjobs'),
            array('job_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        ),
        array('job_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
    )
    ->setComment('GPI BackgroundJobs');
$installer->getConnection()->createTable($table);
 
$table = $installer->getConnection()
    ->newTable($installer->getTable('gpi_tsc/configurations'))
    ->addColumn('config_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Id')
     ->addColumn('config_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
        ), 'Name')
    ->addColumn('config_value', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
        ), 'Value')
    ->addIndex($installer->getIdxName(
            $installer->getTable('gpi_tsc/configurations'),
            array('config_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        ),
        array('config_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
    )
    ->setComment('GPI Configurations');
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('gpi_tsc/quotes'))
    ->addColumn('quote_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => false,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Id')
     ->addColumn('quote_meta', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable'  => false,
        ), 'JobParams')
    ->setComment('GPI Quotes');
$installer->getConnection()->createTable($table);
 
$installer->endSetup();