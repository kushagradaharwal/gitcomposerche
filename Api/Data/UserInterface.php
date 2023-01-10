<?php
/**
 * Extending user module to add additional fields
 */

namespace Ooredoo\UserOrdersacl\Api\Data;

interface UserInterface extends \Magento\User\Api\Data\UserInterface
{
    /**
     * @param array $accessOrderStatus
     * @return $this
     */
	public function setAccessOrderStatus($accessOrderStatus);

    /**
     * @return array
     */
    public function getAccessOrderStatus();
    
    /**
     * @param array $accessOrderStatusComments
     * @return $this
     */
    public function setAccessOrderStatusComments($accessOrderStatusComments);
    
    /**
     * @return array
     */
    public function getAccessOrderStatusComments();

    /**
     * @param array $accessShippingMethod
     * @return $this
     */
    public function setAccessShippingMethod($accessShippingMethod);
    
    /**
     * @return array
     */
    public function getAccessShippingMethod();

    /**
     * @param int $additionalDataAcl
     * @return $this
     */
    public function setAdditionalDataAcl($additionalDataAcl);
    
    /**
     * @return int
     */
    public function getAdditionalDataAcl();

}
