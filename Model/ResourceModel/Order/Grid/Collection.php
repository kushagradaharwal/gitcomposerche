<?php

namespace Ooredoo\UserOrdersacl\Model\ResourceModel\Order\Grid;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as OriginalCollection;
use Vendor\ExtendGrid\Helper\Data as Helper;

class Collection extends OriginalCollection
{
    protected function _renderFiltersBefore()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $adminUser = $objectManager->create('\Magento\Backend\Model\Auth\Session')->getUser();
        $salesOrderTbl = $this->getTable('sales_order');
        $this->getSelect()->joinLeft($salesOrderTbl, 'main_table.entity_id = '.$salesOrderTbl.'.entity_id');
        if($adminUser->getId() > 6) { //skip for super admins
            // set the filters
            $filters = [];
            $filters['status'] = $adminUser->getAccessOrderStatus();
            $filters['shipping_method'] = $adminUser->getAccessShippingMethod();
            foreach ($filters as $filterColumn => $filterValue) {
                if($filterValue) {
                    $this->getSelect()->where($salesOrderTbl.'.'.$filterColumn.' in (?)', $filterValue);
                }
            }
        }
        $this->getSelect()->order('main_table.entity_id', 'DESC');
        parent::_renderFiltersBefore();
    }
}
