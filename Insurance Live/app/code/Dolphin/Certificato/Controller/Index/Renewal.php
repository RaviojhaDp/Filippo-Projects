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
 
class Renewal extends \Magento\Framework\App\Action\Action
{
   
    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
    \Dolphin\Certificato\Model\CertificatoFactory $certificatoModelFactory,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
         parent::__construct($context);
        $this->_objectManager = $objectmanager;
         $this->certificatoModelFactory = $certificatoModelFactory;
          $this->resultPageFactory  = $resultPageFactory;
    }
    
    public function execute()
    {
        $data = $this->getRequest()->getPostValue('data');
          $resultPage = $this->resultPageFactory->create();
        $resultPage->getLayout()->createBlock('Dolphin\Certificato\Block\Index', 'certificato', ['certificato' => ['certificato' => '123']]);
        echo "<pre>";
                
                var_dump($resultPage);
                die;
                return $resultPage;
        /*echo "<prE>";
        print_r($data);
        exit();
         $model = $this->certificatoModelFactory->create()->load($data);
         $model->setStatus(0)->save();
         if(count($model->getData()) > 0){
            }*/
    }
        
}
