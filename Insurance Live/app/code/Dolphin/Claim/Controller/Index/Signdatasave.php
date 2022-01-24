<?php

namespace Dolphin\Claim\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Dolphin\Claim\Model\ClaimFactory;
class Signdatasave extends \Magento\Framework\App\Action\Action {

    
    protected $_messageManager;
    protected $certificatoFactory;
    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\ObjectManagerInterface $objectmanager, \Magento\Framework\Message\ManagerInterface $messageManager,JsonFactory $resultJsonFactory, ClaimFactory $ClaimFactory
    ) {
        parent::__construct($context);
        $this->_objectManager = $objectmanager;
        $this->_messageManager = $messageManager;
        $this->resultJsonFactory = $resultJsonFactory;
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
            $certi = $post['certificato_code'];
           /* if(isset($post['flag'] == "claim")){
            $claimsFactory = $this->claimFactory->create();
            $postUpdate = $claimsFactory->load($certi);
            //$postUpdate->setStatus(1);
            $postUpdate->setDocSignedid($post['header_id']);
            $postUpdate->save();
            }else{*/
            $claimsFactory = $this->claimFactory->create();
            $postUpdate = $claimsFactory->load($certi);
            $postUpdate->setStatusClaim(1);
            $postUpdate->setDocSignedid($post['header_id']);
            $postUpdate->save();
            /*echo "<prE>";
            print_r($postUpdate->getData());
            die;*/
           // }
           
             
             //$id_post_update = '319'; //Example
           /* $certificatoFactory = $this->certificatoFactory->create();
            $postUpdate = $certificatoFactory->load($header_id);
            $postUpdate->setStatus(1);
            $postUpdate->setDocSignedid($post['header_id']);
            $postUpdate->save();*/
           /* $data=$this->certificatoFactory->loadByCertificatoCode($post['certificato_code']);
            $model = $this->certificatoFactory->create();
            $model->setStatus(1);
            $model->setDocSignedid($post['header_id']);
           // $model->setCertificatoId($data['certificato_id']);
            $model->save();*/
           
            $response = ['status' => true,'message' => 'You Claim has been Activated'];
            //$this->_view->renderLayout();
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
            $resultJson->setData($response);
        }
        return $resultJson->setData($response);
    }

}
