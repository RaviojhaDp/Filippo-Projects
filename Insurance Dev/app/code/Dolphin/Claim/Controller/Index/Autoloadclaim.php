<?php
 
/**
 * Grid Admin Cagegory Map Record Save Controller.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Dolphin\Claim\Controller\Index;
 
use Magento\Framework\Controller\Result\JsonFactory;

class Autoloadclaim extends \Magento\Framework\App\Action\Action
{
   protected $regionColFactory;
   protected $resultJsonFactory;

    public function __construct(
	\Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
    JsonFactory $resultJsonFactory,
	\Dolphin\Certificato\Model\CertificatoFactory $claimModelFactory,
     \Magento\Directory\Model\RegionFactory $regionColFactory
	
	) {
		 parent::__construct($context);
         $this->regionColFactory     	= $regionColFactory;
		$this->_objectManager = $objectmanager;
		$this->claimModelFactory = $claimModelFactory;
		$this->resultJsonFactory = $resultJsonFactory;
	}
	
    public function execute()
    {
    	
    	$resultJson = $this->resultJsonFactory->create();
		$data = $this->getRequest()->getPostValue();
		$model = $this->claimModelFactory->create()->load($data['param']);
		$data = $model->getData();
		$data['city'] = ucwords(strtolower(($model->getData("city"))));
                $regions = $this->regionColFactory->create()->load($model->getData('region'));
                if($regions->getData())
        	 	{
                   $data['region']=$regions->getData('name'); 	
                }
                if($data['name_boutique_retailer'] != ''){
                $customerObj = $this->_objectManager->create('Magento\Customer\Model\Customer')->load($data['name_boutique_retailer']);
	            $customerAddress = array();
	            foreach ($customerObj->getAddresses() as $address) {
	                $customerAddress[] = $address->toArray();
	            }
	            
	            if(!empty($customerAddress)){
	             $data['name_boutique_retailer_dup'] = $customerObj->getData('firstname');
	             $data['add_boutique_retailer'] = $customerAddress[0]['street'];
	            }
	        }
		//print_r(json_encode($data,true));
		return $resultJson->setData($data);
	}
}