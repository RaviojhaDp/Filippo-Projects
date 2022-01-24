<?php
 
/**
 * Grid Admin Cagegory Map Record Save Controller.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Dolphin\Certificato\Controller\Index;

use \Magento\Framework\Escaper;
use \Magento\Framework\Mail\Template\TransportBuilder;
 
class Deactivatecerti extends \Magento\Framework\App\Action\Action
{
   
    public function __construct(
	\Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
	\Dolphin\Certificato\Model\CertificatoFactory $certificatoModelFactory,
	TransportBuilder $_transportBuilder,
	Escaper $_escaper
	) {
		parent::__construct($context);
		$this->_objectManager = $objectmanager;
		$this->certificatoModelFactory = $certificatoModelFactory;
		$this->_transportBuilder = $_transportBuilder;
		$this->_escaper = $_escaper;
	}
	
    public function execute()
    {           
		 $data = $this->getRequest()->getPostValue('data');

		 $model = $this->certificatoModelFactory->create()->load($data);  
		 //echo "<pre>";
		 //print_r($model->getData());
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
		$customerId = $model->getCustomerId();
		$customer = $customerFactory->load($customerId);

		$send_mail = $customer->getEmail();
		
           $postData = array();
            $postData['certificato_code'] = $model->getCertificatoCode();
            $postData['brand'] = $model->getBrand();
            $postData['equpiment'] = $model->getEqupiment();
            $postData['model'] = $model->getModel();
            $postData['created_at'] = $model->getCreated_at();
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($postData);
            $error = false;
            $sender = [
                'name' => $this->_escaper->escapeHtml("Damiani Group"),
                'email' => $this->_escaper->escapeHtml("noreply@damianigroupcustomercare.com"),
            ];

            /*Mail to Boutique / Customer for the deactivation*/
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('deactivate_insurance_notify') // this code we have mentioned in the email_templates.xml
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' => '1'
                    ]
                )
                ->setTemplateVars(array(
                    'certificato_code' => $model->getCertificatoCode(),
                    'brand' => $model->getBrand(),
                    'equpiment' => $model->getEqupiment(),
                    'model' => $model->getModel(),
                    'created_at' => $model->getCreated_at()
            ))
                ->setFrom($sender)
                ->addTo($send_mail)
                //->addCc("raviiozhaccfront@yopmail.com")
                ->getTransport();
                try{ 
            	    $transport->sendMessage();
            	  } catch (\Exception $e) {
         	   	    echo $e;
        	 }  
		 //die;        
		 $model->setStatus(4)->save(); //status 4 for certificate disable by Boutique and Retailer account 
		 if(count($model->getData()) > 0){?>
			 <div class="respose">
				<p><?php echo __('Cod : ') ?><span><?php echo $model->getEqupiment();?></span></p>
				<p><?php if($model->getStatus()== '0'){echo __('Cancelled');}else{echo __('Cancelled');} ?></p>
				<p><?php echo __('Activation data') ?></br>
	            <span><?php echo date("d-m-Y", strtotime($model->getCreatedAt()));?></span></p>
			</div>
		 <?php}else{?>
			<div class="respose">
			<p><?php echo __('No search result found.') ?></p>
			</div>
		<?php 
		} 
	}
        
}
