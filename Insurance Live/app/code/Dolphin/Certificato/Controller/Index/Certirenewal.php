<?php

namespace Dolphin\Certificato\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Certirenewal extends \Magento\Framework\App\Action\Action {

    protected $_mediaDirectory;
    protected $_fileUploaderFactory;
    protected $_messageManager;
    protected $resultJsonFactory;
    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\ObjectManagerInterface $objectmanager, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->_objectManager = $objectmanager;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_messageManager = $messageManager;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute() {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultJson = $this->resultJsonFactory->create();
        //return 1;
        
        $post = $this->getRequest()->getPostValue();
        
        if (!$post) {
            $response = ['status' => 'false', 'message' => 'fill all data'];
            return $resultJson->setData($response);
        }
        try {
            $post_renewal = json_decode($this->getRequest()->getPostValue('renewal_data'),true);
             $equpimentResponse = $this->checkEquipmentApi($post_renewal['equpiment'],$post_renewal['brand']);
             $error = json_decode($equpimentResponse, true);
             if($error['return'] == "KO"){
                $response = ['equipment_status' => 'false','message' => $error['message'] ];
                return $resultJson->setData($response);
             }else{ 
             $post = json_decode($this->getRequest()->getPostValue('renewal_data'),true);
             $model = $this->_objectManager->create('Dolphin\Certificato\Model\Certificato')->load($post['certificato_id']);              
             $expire = date('Y-m-d', strtotime('+3 years'));
             $model->setExpireDate($expire);
             $model->save();
        
            //$model->setCertificatoCode($val)->save();
            $session = $this->_objectManager->get('Magento\Customer\Model\Session');
            $session->setWarranty($post['certificato_code']);
            $session->setBrand($post['brand']);
            //$this->_view->loadLayout();
            $response = ['status' => 'true','created_at'=>$model->getCreatedAt(),'certificato_code'=>$post['certificato_code'] ,'message' => 'You Claim has been Activated'];
            //$this->_view->renderLayout();
         }
        } catch (\Exception $e) {
            $response = ['status' => 'false', 'message' => $e->getMessage()];
        }
        return $resultJson->setData($response);
    }
    public function checkEquipmentApi($equpiment, $brand)
    {
        $config = $this->_objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
        $mindamiani = $config->getValue('faber/equipment/mindamiani');
        $maxdamiani = $config->getValue('faber/equipment/maxdamiani');
        $minbliss = $config->getValue('faber/equipment/minbliss');
        $maxbliss = $config->getValue('faber/equipment/maxbliss');
        $minsalvini = $config->getValue('faber/equipment/minsalvini');
        $maxsalvini = $config->getValue('faber/equipment/maxsalvini');
        $minrocca = $config->getValue('faber/equipment/minrocca');
        $maxrocca = $config->getValue('faber/equipment/maxrocca');
        
        $resultJson = $this->resultJsonFactory->create();
        $equpiment = '73131300';
        //73131300
         /*Code to IMPORT CRM*/
        $data = [
            'login' => 'magento',
            'password' => 'arGK!eR5'
        ];
        
        $url = "https://testapp.damianigroup.com/wsservice/authenticate.do";
        $params = '';
        foreach($data as $key=>$value)
            $params .= $key.'='.$value.'&';
            
        $params = trim($params, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            
        $token = tempnam('/tmp','cookie');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $token);
            
        // helpful options
        curl_setopt($ch,CURLOPT_AUTOREFERER, true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
            
        curl_setopt($ch, CURLOPT_URL, $url.'?'.$params ); //Url together with parameters
            
        $response = curl_exec($ch); 
        curl_close($ch);

        $postData = array(
        $postCrmArray["PRODUCT_REFERENCE"] = $equpiment,
        $postCrmArray["LANGUAGE"] = "IT",
        $postCrmArray["PRICE_LIST"] = "EURO_Z001",
    );
     
        $url1 = 'https://testapp.damianigroup.com/wsservice/services/damiani-json/execute?action=GET_PRODUCT_REFERENCE_INFO&';
        $url1 .= "parameter=".(json_encode($postCrmArray));               
        $finalurl = str_replace("\/","/",$url1);
        $urlquotes = str_replace('"','%22',$finalurl);
        $lastfinalurl = str_replace(" ",'%20',$urlquotes);
         
         
        //echo $lastfinalurl."<br>"; 
         $ch = curl_init();         
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($ch, CURLOPT_COOKIEFILE, $token);
         curl_setopt($ch,CURLOPT_AUTOREFERER, true);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
         //curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:application/json'));
         curl_setopt($ch, CURLOPT_URL, $lastfinalurl); //Url together with parameters
         $response = curl_exec($ch);
         $responses = json_decode($response,true);
        
         if(isset($responses['parameter'])){  
         $branda = $responses['parameter']['BRAND'];
         $cat = $responses['parameter']['MACRO_FAMILY'];
         if(strtolower($branda) == "damiani"){
         if(isset($cat) && $cat == '001'){ 
            return '{"equipment_status" : "true","message" : "Success","return":"OK"}';
           }else{
              return '{"equipment_status" : "false","message" : "Price not in range","return":"KO"}';
              }
           }
           else{
            return '{"equipment_status" : "false","message" : "Price not in range","return":"KO"}';
          }
        }
        else{
            return $response;
        }
         
         //exit("SAP API die");
    
    }

}
