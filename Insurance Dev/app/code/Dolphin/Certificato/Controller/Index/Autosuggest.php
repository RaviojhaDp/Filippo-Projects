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
 
class Autosuggest extends \Magento\Framework\App\Action\Action
{
   
    public function __construct(
	\Magento\Framework\App\Action\Context $context,
	 \Magento\Customer\Model\Session $customerSession,
    \Magento\Framework\ObjectManagerInterface $objectmanager
	) {
		 parent::__construct($context);
		$this->_objectManager = $objectmanager;
		$this->customerSession = $customerSession;
	}
	
    public function execute()
    {
    	$customerId = $this->customerSession->getCustomer()->getId();
    	$group = $this->customerSession->getCustomer()->getGroupId();
		$dataget = key($this->getRequest()->getPostValue());
		$data = trim(str_replace('_', ' ', $dataget));
		
		$model = $this->_objectManager->create('Dolphin\Certificato\Model\Certificato')->getCollection();
		//	$model->addFieldToFilter('name',array('like' => '%'.$data.'%'));
		$model->addFieldToFilter(array('name','surname'),
                                    array(
                                        array('like'=>'%'.$data.'%'), 
                                        array('like'=>'%'.$data.'%')
                                    ));
		$model->addFieldToFilter('status',array('eq' => '1'));
		if($group == '3' || $group == '4'){
		$model->addFieldToFilter('name_boutique_retailer',array('eq' => $customerId));
		}
		/*if($group == '4'){
		$model->addFieldToFilter('customer_group_id',array('eq' => '4'));
		}
		echo $model->getSelect();
		die;*/
		if(count($model->getData()) > 0){
		foreach($model->getData() as $row){
			?>
			<li class='result' data-value='<?php echo $row['certificato_id'] ?>'><?php echo $row['name']." ".$row['surname']; ?></li> 
		 <?php }
		}
		else{
			echo __('No search result found.');
		}
        exit;
	}
}