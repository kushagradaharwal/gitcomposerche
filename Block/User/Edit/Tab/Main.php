<?php

namespace Ooredoo\UserOrdersacl\Block\User\Edit\Tab;

class Main
{
	protected $_statusFactory;
	protected $_shippingConfig;
	protected $_scopeConfig;
	protected $_registry;

	public function __construct(
		\Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusFactory,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Registry $registry,
		\Magento\Shipping\Model\Config $shippingConfig
	)
	{
		$this->_statusFactory = $statusFactory;
		$this->_shippingConfig = $shippingConfig;
	    $this->_scopeConfig = $scopeConfig;
	    $this->_registry = $registry;
	}
    /**
     * Get form HTML
     *
     * @return string
     */
    public function aroundGetFormHtml(
        \Magento\User\Block\User\Edit\Tab\Main $subject,
        \Closure $proceed
    )
    {
    	/** @var $model \Magento\User\Model\User */
        $model = $this->_registry->registry('permissions_user');

        $form = $subject->getForm();
        if (is_object($form)) {
            $baseFieldset = $form->addFieldset(
            	'6d_base_fieldset_user', 
            	['legend' => __('ACL Filters')],
            	'base_fieldset'
            );
            
            // Get all order statuses
	        $statuses = $this->_statusFactory->create()->toOptionArray();

	        // filter fields
	        $baseFieldset->addField(
	            'access_order_status',
	            'multiselect',
	            [
	                'name' => 'access_order_status',
	                'label' => __('Access Order Status'),
	                'id' => 'access_order_status',
	                'title' => __('Access Order Status'),
	                'required' => true,
	                'class' => 'multiselect',
	                'values' => $statuses
	            ]
	        );
	        $baseFieldset->addField(
	            'access_order_status_comments',
	            'multiselect',
	            [
	                'name' => 'access_order_status_comments',
	                'label' => __('Access Order Status Comments'),
	                'id' => 'access_order_status_comments',
	                'title' => __('Access Order Status Comments'),
	                'required' => true,
	                'class' => 'multiselect',
	                'values' => $statuses
	            ]
	        );
	        $baseFieldset->addField(
	            'access_shipping_method',
	            'multiselect',
	            [
	                'name' => 'access_shipping_method',
	                'label' => __('Shipping Method ACL'),
	                'id' => 'access_shipping_method',
	                'title' => __('Shipping Method ACL'),
	                'required' => true,
	                'class' => 'multiselect',
	                'values' => $this->getActiveShippingMethods()
	            ]
	        );
	        $baseFieldset->addField(
	            'additional_data_acl',
	            'select',
	            [
	                'name' => 'additional_data_acl',
	                'label' => __('Additional Data ACL'),
	                'id' => 'additional_data_acl',
	                'title' => __('Additional Data ACL'),
	                'required' => true,
	                'class' => 'select',
	                'values' => [
	                	[
						 	'value' => '0',
						 	'label' => 'No Access'
					 	],
	                	[
						 	'value' => '1',
						 	'label' => 'Read'
					 	], 
					 	[
						 	'value' => '2',
						 	'label' => 'Read/Write'
					 	]
				 	]
	            ]
	        );

	        //set values after page load
        	$data = [];
	        $customFields = [
	        	'access_order_status' => 'multiselect',
	        	'access_order_status_comments' => 'multiselect',
	        	'access_shipping_method' => 'multiselect',
	        	'additional_data_acl' => 'select'
	        ];
	        foreach ($customFields as $field => $type) {
	        	$value = $model->getData($field);
        		$data[$field] = ($type == 'multiselect' && ! is_array($value)) ? explode(',', $value) : $value;
	        }
	        $form->addValues($data);

            $subject->setForm($form);
        }
        if (is_object($form)) {
            $baseFieldset = $form->addFieldset(
            	'6d_base_fieldset_customer_user', 
            	['legend' => __('B2B Onboarding Customer ACL Filters')],
            	'base_fieldset'
            );
            
            // Get all order statuses
	        $statuses = $this->_statusFactory->create()->toOptionArray();

	        // filter fields
	        $baseFieldset->addField(
	            'corporate_customer_status',
	            'multiselect',
	            [
	                'name' => 'corporate_customer_status',
	                'label' => __('Corporate Customer Status'),
	                'id' => 'corporate_customer_status',
	                'title' => __('Corporate Customer Status'),
	                'required' => true,
	                'class' => 'multiselect',
	                'values' => $this->getCorporateCustomerStatus()
	            ]
	        );

	        $baseFieldset->addField(
	            'customer_data_acl',
	            'select',
	            [
	                'name' => 'customer_data_acl',
	                'label' => __('Customer Data ACL'),
	                'id' => 'customer_data_acl',
	                'title' => __('Customer Data ACL'),
	                'required' => true,
	                'class' => 'select',
	                'values' => [
	                	[
						 	'value' => '0',
						 	'label' => 'No Access'
					 	],
	                	[
						 	'value' => '1',
						 	'label' => 'Read'
					 	], 
					 	[
						 	'value' => '2',
						 	'label' => 'Read/Write'
					 	]
				 	]
	            ]
	        );

	        
	        //set values after page load
        	$data = [];
	        $customFields = [
	        	'corporate_customer_status' => 'multiselect',
	       		'customer_data_acl' => 'select'

	        ];
	        foreach ($customFields as $field => $type) {
	        	$value = $model->getData($field);
        		$data[$field] = ($type == 'multiselect' && ! is_array($value)) ? explode(',', $value) : $value;
	        }
	        $form->addValues($data);
            $subject->setForm($form);
        }


        return $proceed();
    }

    /**
     * Get all active shipping methods
     * @return array 
     */
    public function getActiveShippingMethods()
    {
        $shippingMethods = [];
        $activeCarriers = $this->_shippingConfig->getAllCarriers();
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        foreach($activeCarriers as $carrierCode => $carrierModel) {
           	if( $carrierMethods = $carrierModel->getAllowedMethods() ) {
               $carrierTitle =$this->_scopeConfig->getValue('carriers/'.$carrierCode.'/title');
               foreach ($carrierMethods as $methodCode => $method) {
               		$shippingMethods[] = [
               			'label' => $carrierTitle.' '.$methodCode,
               			'value' => $carrierCode.'_'.$methodCode
               		];
               	}
           	}
        }
        return $shippingMethods;        
    }
    /**
     * Get all active shipping methods
     * @return array 
     */
    public function getCorporateCustomerStatus()
    {

			$status = array();
	        $status[0] =array(
	            'value' => '',
	            'label' => 'Please Select'
	        );
	        $status[1] =array(
	            'value' => 'pending',
	            'label' => 'Pending'
	        );
	        $status[2] =array(
	            'value' => 'approved',
	            'label' => 'Approved'
	        );
	        $status[3] =array(
	            'value' => 'reject',
	            'label' => 'Reject'
	        );
	        $status[4] =array(
	            'value' => 'reupload document',
	            'label' => 'Reupload Document'
	        );
       return $status;             
    }

}
