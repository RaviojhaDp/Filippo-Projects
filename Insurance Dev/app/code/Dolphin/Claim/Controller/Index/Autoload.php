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
 
class Autoload extends \Magento\Framework\App\Action\Action
{
   
    public function __construct(
	\Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
 	\Dolphin\Claim\Model\Claim $claimForm2Model,
	\Dolphin\Certificato\Model\CertificatoFactory $claimModelFactory
	) {
		 parent::__construct($context);
		$this->_objectManager = $objectmanager;
		$this->claimModelFactory = $claimModelFactory;
		$this->claimForm2Model = $claimForm2Model;
	}
	
 
	public function execute()
    {
		$data = $this->getRequest()->getPostValue();
		$model = $this->claimModelFactory->create()->getCollection();
		$model->addFieldToFilter('customer_id',array('eq' => $data['param'] ));
		$model->addFieldToFilter('status',array('eq' => '1' ));
		if(isset($data['cat_name'])){
			$cat_name = strtolower($data['cat_name']);	
			$model->addFieldToFilter('brand',array('eq' => $cat_name ));
			$model->addFieldToFilter('status',array('eq' => '1' ));
		}

		$html = '';
         $html .= '<option value="">'. __('Search Warranty').'</option>';	
         foreach($model->getData() as $name){

         	$cc = substr($name['certificato_code'], 1);
         	$created_at=date("d/m/Y", strtotime($name['created_at'])); 
            $form2Check = $this->_objectManager->create('\Dolphin\Claim\Model\Claim')->getCollection()
               ->addFieldToFilter('certificato_id',array('eq'=>$cc))
               ->addFieldToFilter('status_claim',array('eq'=>'1'));
             if(!(count($form2Check->getData())) > '0'){
         	  $html .= '<option value = "'.$name['certificato_id'].'">'. $name['certificato_code'] ." ".$created_at.'</option>';
         	 }	
         }
		echo $html;
		//print_r(json_encode($data,true));
	}
}