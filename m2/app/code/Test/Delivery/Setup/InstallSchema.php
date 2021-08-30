<?php

namespace Test\Delivery\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * Class InstallSchema
 * @package Test\Delivery\Setup
 */
class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('test_delivery_time_interval')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('test_delivery_time_interval'))
                ->addColumn(
                    'time_interval_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true
                    ],
                    'Time Interval ID'
                )
                ->addColumn('from', Table::TYPE_TEXT, 50, ['nullable => false'], 'From time')
                ->addColumn('to', Table::TYPE_TEXT, 50, ['nullable => false'], 'To time')
                ->addColumn('position', Table::TYPE_INTEGER, null, [], 'Position')
                ->setComment('Time Interval  Table');
            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('test_delivery_time_interval_store')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('test_delivery_time_interval_store'))
                ->addColumn(
                    'store_id',
                    Table::TYPE_SMALLINT,
                    null,
                    [
                        'identity' => false,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true
                    ],
                    'Store ID'
                )
                ->addColumn(
                    'time_interval_id',
                    Table::TYPE_SMALLINT,
                    null,
                    [
                        'identity' => false,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true
                    ],
                    'Time Interval ID'
                )
                ->setComment('Time Interval  Table Store');
            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('test_shipping_delivery')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('test_shipping_delivery'))
                ->addColumn(
                    'delivery_id',
                    Table::TYPE_SMALLINT,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true
                    ],
                    'Store ID'
                )
                ->addColumn('quote_id', Table::TYPE_INTEGER, null, ['nullable => true'], 'Quote Id')
                ->addColumn('order_id', Table::TYPE_INTEGER, null, ['nullable => true'], 'Order Id')
                ->addColumn('delivery_date', Table::TYPE_TEXT, 50, ['nullable => true'], 'Delivery Date')
                ->addColumn('delivery_time', Table::TYPE_TEXT, 50, ['nullable => true'], 'Delivery Time')
                ->addColumn('delivery_comment', Table::TYPE_TEXT, 50, ['nullable => true'], 'Delivery Comment')
                ->setComment('Shipping Delivery');
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
