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
 
class Autosuggest extends \Magento\Framework\App\Action\Action
{
   
    public function __construct(
	\Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
     \Magento\Customer\Model\SessionFactory $sessionFactory
	) {
		 parent::__construct($context);
		$this->_objectManager = $objectmanager;
         $this->sessionFactory = $sessionFactory;
	}
	
    public function execute()
    {
        $sessionModel = $this->sessionFactory->create();
        $customerId = $sessionModel->getCustomer()->getId();
		$dataget = key($this->getRequest()->getPostValue());
        $data = trim(str_replace('_', ' ', $dataget));

		$model = $this->_objectManager->create('Dolphin\Certificato\Model\Certificato')->getCollection();
        $sessionGroupId = $sessionModel->getCustomer()->getGroupId();
        if($sessionGroupId == '5'){
        	
		$model->addFieldToFilter(array('name','surname'),
                                    array(
                                        array('like'=>'%'.$data.'%'), 
                                        
                                        array('like'=>'%'.$data.'%')
                                    ));
        $model->addFieldToFilter('customer_id',array('eq' => $customerId ));
    }else{
        $model->addFieldToFilter(array('name','surname'),
                                    array(
                                        array('like'=>'%'.$data.'%'), 
                                        
                                        array('like'=>'%'.$data.'%')
                                    ));
        $model->addFieldToFilter('name_boutique_retailer',array('eq' => $customerId ));
        $model->addFieldToFilter('status',array('eq' => '1' ))->getSelect()->group('customer_id');
    }
        //echo $model->getSelect();die;
       /* echo "<pre>";
        print_r($model->getData());
        die;*/
		if(count($model->getData()) > 0){
		
		foreach($model->getData() as $row){
			?>
			<li class='result' data-certi-value='<?php echo $row['certificato_id'] ?>' data-value='<?php echo $row['customer_id'] ?>'><?php echo $row['name']." ".$row['surname']; ?></li> 
		 <?php }
		}
		else{
			echo  __('No search result found'); 
		}
		exit;
	}
        
   
}