<?php

namespace Dolphin\Certificato\Block\Index;

use Dolphin\Certificato\Model\CertificatoFactory;
use Magento\Framework\Data\Collection;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Index extends \Magento\Framework\View\Element\Template {

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Customer\Model\SessionFactory $sessionFactory,CertificatoFactory $CertificatoFactory, array $data = []) {
		$this->_modelCertificatoFactory = $CertificatoFactory;
         $this->sessionFactory = $sessionFactory;
        parent::__construct($context, $data);
        
 
         $collection = $this->_modelCertificatoFactory->create()->getCollection();
            $this->setCollection($collection);
            $this->pageConfig->getTitle()->set(__('Warranty Success'));

    }

         
            /**
             * @return string
             */
            // method for get pager html
            public function getPagerHtml()
            {
                return $this->getChildHtml('pager');
            } 
            

	public function getCertificatoList()
    {
		
      $optionCollection = $this->_modelCertificatoFactory->create()->getCollection();
       return $optionCollection;
	}
	
    protected function _prepareLayout()
    {
         parent::_prepareLayout();
        if ($this->getCollection()) {
            $sessionCustomerId = $this->sessionFactory->create()->getCustomer()->getId();
           $sessionGroupId = $this->sessionFactory->create()->getCustomer()->getGroupId();
           if($sessionGroupId == '5'){
            $this->getCollection()
                ->addFieldToFilter('customer_group_id', $sessionGroupId)
                ->addFieldToFilter('customer_id', $sessionCustomerId)
                 ->addFieldToFilter('doc_signedid',['neq' => ''])
                ->addFieldToFilter('status', '1')
                ->setOrder('certificato_id','DESC');
              }else{
             $this->getCollection()
                ->addFieldToFilter('name_boutique_retailer', $sessionCustomerId)
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('doc_signedid',['neq' => ''])
                ->setOrder('certificato_id','DESC');
                // ->setOrder('brand','ASC');
                }
              /* echo  $this->getCollection()->getSelect();
                die;*/
                    // create pager block for collection 
                    $pager = $this->getLayout()->createBlock(
                        'Magento\Theme\Block\Html\Pager',
                        'certificato.grid.record.pager'
                    )->setCollection(
                        $this->getCollection() // assign collection to pager
                    );
                    $this->setChild('pager', $pager);// set pager block in layout
                }
                return $this;
    }
	
	

}