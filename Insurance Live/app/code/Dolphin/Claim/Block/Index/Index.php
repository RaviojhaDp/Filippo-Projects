<?php

namespace Dolphin\Claim\Block\Index;

use Dolphin\Claim\Model\ClaimFactory;

class Index extends \Magento\Framework\View\Element\Template {

    protected $directoryBlock;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Customer\Model\SessionFactory $sessionFactory, ClaimFactory $ClaimFactory, \Magento\Directory\Block\Data $directoryBlock, array $data = []) {
        $this->ClaimFactory = $ClaimFactory;
         $this->sessionFactory = $sessionFactory;
        $this->directoryBlock = $directoryBlock;
        parent::__construct($context, $data);
          $collection = $this->ClaimFactory->create()->getCollection();
            $this->setCollection($collection);
            $this->pageConfig->getTitle()->set(__('Claim Success'));
    }

    public function getClaimList() {
        $optionCollection = $this->ClaimFactory->create()->getCollection();
        //echo "ravi __>: "."<pre>";
        //print_r($optionCollection->getData());
        //die;
        return $optionCollection;
    }

    protected function _prepareLayout() {
         parent::_prepareLayout();
        if ($this->getCollection()) {
            $sessionCustomerId = $this->sessionFactory->create()->getCustomer()->getId();
           $sessionGroupId = $this->sessionFactory->create()->getCustomer()->getGroupId();
        if($sessionGroupId == '5'){
            $this->getCollection()
                ->addFieldToFilter('customer_group_id', $sessionGroupId)
                ->addFieldToFilter('customer_id', $sessionCustomerId)
                ->addFieldToFilter('status_claim', '1')
                ->setOrder('claim_id','DESC');
              }else{
             $this->getCollection()
                ->addFieldToFilter('name_boutique_retailer', $sessionCustomerId)
                ->addFieldToFilter('status_claim', '1')
              ->setOrder('claim_id','DESC');
                }
            /* $this->getCollection()
                 ->addFieldToFilter('customer_group_id', $sessionGroupId)
                ->addFieldToFilter('customer_id', $sessionCustomerId)
                ->addFieldToFilter('status', '1')
                ->setOrder('claim_id','DESC');*/
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
/**
             * @return string
             */
            // method for get pager html
            public function getPagerHtml()
            {
                return $this->getChildHtml('pager');
            } 

    public function getRegion() {
        $region = $this->directoryBlock->getRegionHtmlSelect();
        return $region;
    }

    public function getCountryAction() {
        return $this->getUrl('certificato/index/country', ['_secure' => true]);
    }

}
