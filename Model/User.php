<?php
/**
 * Extending user module to add additional fields
 */

namespace Ooredoo\UserOrdersacl\Model;

use Ooredoo\UserOrdersacl\Api\Data\UserInterface;

class User extends \Magento\User\Model\User implements UserInterface
{
	/**
     * @inheritDoc
     */
	public function getAccessOrderStatus()
    {
        $value = $this->_getData('access_order_status');
        return (is_array($value )) ? $value : explode(',', $value);
    }

    /**
     * @inheritDoc
     */
    public function setAccessOrderStatus($accessOrderStatus)
    {
        return $this->setData('access_order_status', implode(',', $accessOrderStatus));
    }

    /**
     * @inheritDoc
     */
    public function getAccessOrderStatusComments()
    {
        $value = $this->_getData('access_order_status_comments');
        return (is_array($value)) ? $value : explode(',', $value);
    }

    /**
     * @inheritDoc
     */
    public function SetAccessOrderStatusComments($accessOrderStatusComments)
    {
        return $this->setData('access_order_status_comments', implode(',', $accessOrderStatusComments));
    }

    /**
     * @inheritDoc
     */
    public function getAccessShippingMethod()
    {
        $value = $this->_getData('access_shipping_method');
        return (is_array($value)) ? $value : explode(',', $value);
    }

    /**
     * @inheritDoc
     */
    public function setAccessShippingMethod($accessShippingMethod)
    {
        return $this->setData('access_shipping_method', implode(',', $accessShippingMethod));
    }

    /**
     * @inheritDoc
     */
    public function getAdditionalDataAcl()
    {
        return $this->_getData('additional_data_acl');
    }

    /**
     * @inheritDoc
     */
    public function setAdditionalDataAcl($additionalDataAcl)
    {
        return $this->setData('additional_data_acl', $additionalDataAcl);
    }

    /**
     * @inheritDoc
     */
    public function getCorporateCustomerStatus()
    {
        $value = $this->_getData('corporate_customer_status');
        return (is_array($value )) ? $value : explode(',', $value);
    }

    /**
     * @inheritDoc
     */
    public function setCorporateCustomerStatus($corporateCustomerStatus)
    {
        return $this->setData('corporate_customer_status', implode(',', $corporateCustomerStatus));
    }
    /**
     * @inheritDoc
     */
    public function getCustomerDataAcl()
    {
        return $this->_getData('customer_data_acl');
    }

    /**
     * @inheritDoc
     */
    public function setCustomerDataAcl($customerDataAcl)
    {
        return $this->setData('customer_data_acl', $customerDataAcl);
    }    

	/**
     * Processing data before model save
     *
     * @return $this
     */
    public function beforeSave()
    {
            $data = [
                'access_order_status' => implode(',', $this->getAccessOrderStatus()),
                'access_order_status_comments' => implode(',', $this->getAccessOrderStatusComments()),
                'access_shipping_method' => implode(',', $this->getAccessShippingMethod()),
                'corporate_customer_status' => implode(',', $this->getCorporateCustomerStatus())
            ];
            $this->addData($data);
        return parent::beforeSave();
    }
}
