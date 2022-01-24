<?php

namespace Dolphin\Certificato\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Dolphin\Certificato\Model\CertificatoFactory;
use Dolphin\Claim\Model\ClaimFactory;
class Signdatasave extends \Magento\Framework\App\Action\Action {

    
    protected $_messageManager;
    protected $certificatoFactory;
    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\ObjectManagerInterface $objectmanager, \Magento\Framework\Message\ManagerInterface $messageManager,JsonFactory $resultJsonFactory,CertificatoFactory $CertificatoFactory,ClaimFactory $ClaimFactory
    ) {
        parent::__construct($context);
        $this->_objectManager = $objectmanager;
        $this->_messageManager = $messageManager;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->certificatoFactory = $CertificatoFactory;
         $this->claimFactory = $ClaimFactory;
    }

    public function execute() {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultJson = $this->resultJsonFactory->create();
        //return 1;
        
        $post = $this->getRequest()->getPostValue();
        
        if (!$post) {
            $response = ['status' => false, 'message' => 'Wrong data value'];
            return $resultJson->setData($response);
        }
        try {
            if(is_numeric($post['certificato_code'])){
        $certi = $post['certificato_code'];
            }else{
        $certi = substr($post['certificato_code'], 1);
            }
            
           /* if(isset($post['flag'] == "claim")){
            $claimsFactory = $this->claimFactory->create();
            $postUpdate = $claimsFactory->load($certi);
            //$postUpdate->setStatus(1);
            $postUpdate->setDocSignedid($post['header_id']);
            $postUpdate->save();
            }else{*/
            $expire = date('Y-m-d', strtotime('+3 years'));
             $finalExpire =  date('Y-m-d', strtotime('-1 day', strtotime($expire)));
            $certificatoFactory = $this->certificatoFactory->create();
            $postUpdate = $certificatoFactory->load($certi);
            $postUpdate->setStatus('1');
            $postUpdate->setExpireDate($finalExpire);
            $postUpdate->setDocSignedid($post['header_id']);
            $postUpdate->save();
           // }
           
          
           
            $response = ['status' => true,'message' => 'You Claim has been Activated'];
            //$this->_view->renderLayout();
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
            $resultJson->setData($response);
        }
        return $resultJson->setData($response);
    }

}
