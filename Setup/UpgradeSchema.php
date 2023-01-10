<?php

namespace Ooredoo\UserOrdersacl\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * {@inheritdoc}
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        
        $installer = $setup;

        $installer->startSetup();

        if(version_compare($context->getVersion(), '1.0.1', '<')) {
            $connection = $installer->getConnection();

            $connection->addColumn(
                $installer->getTable( 'admin_user' ),
                'access_order_status',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Access order status'
                ]
            );

            $connection->addColumn(
                $installer->getTable( 'admin_user' ),
                'access_order_status_comments',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Access order status on comments role'
                ]
            );
        }

        if(version_compare($context->getVersion(), '1.0.2', '<')) {
            $connection = $installer->getConnection();

            $connection->addColumn(
                $installer->getTable( 'admin_user' ),
                'access_order_status',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Access order status'
                ]
            );
            $connection->addColumn(
                $installer->getTable( 'admin_user' ),
                'access_order_status_comments',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Access order status on comments role'
                ]
            );
            $connection->addColumn(
                $installer->getTable( 'admin_user' ),
                'access_shipping_method',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Shipping Method ACL'
                ]
            );
            $connection->addColumn(
                $installer->getTable( 'admin_user' ),
                'additional_data_acl',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => false,
                    'comment' => 'Additional Data ACL'
                ]
            );
        }
        if(version_compare($context->getVersion(), '1.0.3', '<')) {
            $connection = $installer->getConnection();

            $connection->addColumn(
                $installer->getTable( 'admin_user' ),
                'corporate_customer_status',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Corporate Customer Status'
                ]
            );
            $connection->addColumn(
                $installer->getTable( 'admin_user' ),
                'customer_data_acl',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => false,
                    'comment' => 'Customer Data ACL'
                ]
            );

        }
        $installer->endSetup();
    }
}