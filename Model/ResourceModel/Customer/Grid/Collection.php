<?php

namespace Ooredoo\UserOrdersacl\Model\ResourceModel\Customer\Grid;

use Magento\Customer\Model\ResourceModel\Grid\Collection as OriginalCollection;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;

class Collection extends OriginalCollection
{

    protected function _initSelect()
    {
        $this->addFilterToMap('corporate_customer_status', 'main_table.corporate_customer_status');

        parent::_initSelect();
    }

    protected function _renderFiltersBefore()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $adminUser = $objectManager->create('\Magento\Backend\Model\Auth\Session')->getUser();

        if(!empty($adminUser->getData('corporate_customer_status'))){

            $customerGridFLatTbl = $this->getTable('customer_grid_flat');
            //$this->getSelect()->join($customerGridFLatTbl, 'main_table.entity_id = '.$customerGridFLatTbl.'.entity_id');
           // if($adminUser->getId() > 6) { //skip for super admins
                // set the filters
                $filters = [];
                $filters['corporate_customer_status'] = $adminUser->getCorporateCustomerStatus();
              
                foreach ($filters as $filterColumn => $filterValue) {
                    if($filterValue) {
                        $this->getSelect()->where('main_table'.'.'.$filterColumn.' in (?)', $filterValue);
                    }
                }
           // }
            $this->getSelect()->order('main_table.entity_id', 'DESC');
           
        }
        parent::_renderFiltersBefore();

    }
}
