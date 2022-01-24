<?php
namespace Dolphin\Certificato\Controller\Index;
 
class Country extends \Magento\Framework\App\Action\Action
{
    protected $resultJsonFactory;
    
    protected $regionColFactory;
 
            public function __construct(
            \Magento\Framework\App\Action\Context $context,
            \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
            \Magento\Directory\Model\RegionFactory $regionColFactory)
    {       
            $this->regionColFactory         = $regionColFactory;
            $this->resultJsonFactory        = $resultJsonFactory;
            parent::__construct($context);
            }
 
            public function execute()
            {
            $this->_view->loadLayout();
            $this->_view->getLayout()->initMessages();
            $this->_view->renderLayout();
            $country = $this->getRequest()->getParam('country');
            $result = $this->resultJsonFactory->create();
            $regions = $this->regionColFactory->create()->getCollection()->addFieldToFilter('country_id',$this->getRequest()->getParam('country'));
            
             $html = '';
            
             if(count($regions) > 0)
             {
                         $html.='<option disabled selected="selected" value="">'. __('Province').'</option>';
                         
                         foreach($regions as $state)
                         {

                           /* if($country == "IT" && $state->getRegionId() == '613'){
                                $state->getName() = "Forlì - Cesena";
                            }*/
                                     $html.=    '<option  value="'.$state->getRegionId().'">'.$state->getName().'</option>';
                         }
             }
             return $result->setData(['success' => true,'country'=>$country,'value'=>$html]);
   }
}
