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
 
class Equipment extends \Magento\Framework\App\Action\Action
{
   
    public function __construct(
	\Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager
	) {
		 parent::__construct($context);
		$this->_objectManager = $objectmanager;
	}
	
    public function execute()
    {
		$data = $this->getRequest()->getPostValue('data');
		$model = $this->_objectManager->create('Dolphin\Certificato\Model\Certificato')->getCollection();
		$model->addFieldToFilter('equpiment',array('eq' => $data ));
		$model->addFieldToFilter('status',array('eq' => '1' ));
		if(count($model->getData()) > 0){
			 echo '{"data":true}';
			}
		else{
			 echo '{"data":false}';
		}
	}
       
}