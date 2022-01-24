<?php

namespace Dolphin\Certificato\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Certi extends \Magento\Framework\App\Action\Action {

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
        $post = $this->getRequest()->getPostValue();
        
        if (!$post) {
            $response = ['status' => 'false', 'message' => 'fill all data'];
            return $resultJson->setData($response);
        }
        try {
            if(strtolower($post['brand']) == "bliss"){
                $code = $post['model'];
            }elseif(strtolower($post['brand']) == "calderoni"){
                 $code = $post['stone_code'];
            }else{
                $code = $post['equpiment']; 
            }
            if(strtolower($post['storename'])== "italiano"){  
                  $lang = "it"; 
              }else{  
                  $lang = "en"; 
              }
             $equpimentResponse = $this->checkEquipmentApi($code ,$post['brand'],$post['storename']);
             $error = json_decode($equpimentResponse, true);
             if($error['return'] == "KO"){
                $response = ['equipment_status' => 'false','message' => $error['message'] ];
                return $resultJson->setData($response);
    
             }else{  
             if (isset($_FILES['filetoupload']['name']) && $_FILES['filetoupload']['name'] != '') {
                try {
                    $target = $this->_mediaDirectory->getAbsolutePath('certi_upload/');
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'filetoupload']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'zip', 'doc','pdf']);
                    $uploader->setAllowRenameFiles(true);
                    $result = $uploader->save($target);
                    $fullname = $_FILES['filetoupload']['name'];
                    $post['filetoupload'] = $uploader->getUploadedFileName();
                    //$post['filetoupload'] = $fullname;
                } catch (\Exception $e) {
                    
                }
            }
            $model = $this->_objectManager->create('Dolphin\Certificato\Model\Certificato');

            $cat_name = $this->getRequest()->getPostValue("parent_cat");
            $prefix = '';
            if (strtolower($cat_name) == "damiani") {
                $prefix = "D";
            }
            if (strtolower($cat_name) == "salvini") {
                $prefix = "S";
            }
            if (strtolower($cat_name) == "rocca") {
                $prefix = "R";
            }
            if (strtolower($cat_name) == "bliss") {
                $prefix = "B";
            }
            if (strtolower($cat_name) == "calderoni") {
                $prefix = "C"; 
            }
            $expire = date('Y-m-d', strtotime('+3 years'));
             
            /*marketing question last added*/
            if(isset($post['partner_dob']) && !empty($post['partner_dob'])){
                
              $post['partner_dob'] = str_replace('/', '-', $post['partner_dob'] );   
              $post['partner_dob'] = date('Y-m-d', strtotime($post['partner_dob']));
            }else{
               $post['partner_dob'] = '';
            }

             if(isset($post['partner_dob_single']) && !empty($post['partner_dob_single'])){
              $post['partner_dob_single'] = str_replace('/', '-', $post['partner_dob_single'] );   
              $post['partner_dob_single'] = date('Y-m-d', strtotime($post['partner_dob_single']));
            }else{
               $post['partner_dob_single'] = '';
            }
            if(isset($post['wedding_anniversary']) && !empty($post['wedding_anniversary'])){
               $post['wedding_anniversary'] = str_replace('/', '-', $post['wedding_anniversary'] );   
               $post['wedding_anniversary'] = date('Y-m-d', strtotime($post['wedding_anniversary']));
            }else{
               $post['wedding_anniversary'] = '';
            }
            /*-------------------------------FIRST STATUS------------------*/
             if(isset($post['first_chidren_dob_one']) && !empty($post['first_chidren_dob_one'])){
               $post['first_chidren_dob_one'] = str_replace('/', '-', $post['first_chidren_dob_one'] );   
               $post['first_chidren_dob_one'] = date('Y-m-d', strtotime($post['first_chidren_dob_one']));
            }else{
               $post['first_chidren_dob_one'] = '';
            }
            if(isset($post['first_chidren_dob_two']) && !empty($post['first_chidren_dob_two'])){
               $post['first_chidren_dob_two'] = str_replace('/', '-', $post['first_chidren_dob_two'] );   
               $post['first_chidren_dob_two'] = date('Y-m-d', strtotime($post['first_chidren_dob_two']));
            }else{
               $post['first_chidren_dob_two'] = '';
            }
            if(isset($post['first_chidren_dob_three']) && !empty($post['first_chidren_dob_three'])){
               $post['first_chidren_dob_three'] = str_replace('/', '-', $post['first_chidren_dob_three'] );   
               $post['first_chidren_dob_three'] = date('Y-m-d', strtotime($post['first_chidren_dob_three']));
            }else{
               $post['first_chidren_dob_three'] = '';
            }
            if(isset($post['first_chidren_dob_four']) && !empty($post['first_chidren_dob_four'])){
               $post['first_chidren_dob_four'] = str_replace('/', '-', $post['first_chidren_dob_four'] );   
               $post['first_chidren_dob_four'] = date('Y-m-d', strtotime($post['first_chidren_dob_four']));
            }else{
               $post['first_chidren_dob_four'] = '';
            }
            if(isset($post['first_chidren_dob_five']) && !empty($post['first_chidren_dob_five'])){
               $post['first_chidren_dob_five'] = str_replace('/', '-', $post['first_chidren_dob_five'] );   
               $post['first_chidren_dob_five'] = date('Y-m-d', strtotime($post['first_chidren_dob_five']));
            }else{
               $post['first_chidren_dob_five'] = '';
            }
            if(isset($post['first_chidren_dob_six']) && !empty($post['first_chidren_dob_six'])){
               $post['first_chidren_dob_six'] = str_replace('/', '-', $post['first_chidren_dob_six'] );   
               $post['first_chidren_dob_six'] = date('Y-m-d', strtotime($post['first_chidren_dob_six']));
            }else{
               $post['first_chidren_dob_six'] = '';
            }
            /*FIRST STATUS*/
             /*-------------------------------Single STATUS------------------*/
             if(isset($post['engaged_chidren_dob_one']) && !empty($post['engaged_chidren_dob_one'])){
               $post['engaged_chidren_dob_one'] = str_replace('/', '-', $post['engaged_chidren_dob_one'] );   
               $post['engaged_chidren_dob_one'] = date('Y-m-d', strtotime($post['engaged_chidren_dob_one']));
            }else{
               $post['engaged_chidren_dob_one'] = '';
            }
            if(isset($post['engaged_chidren_dob_two']) && !empty($post['engaged_chidren_dob_two'])){
               $post['engaged_chidren_dob_two'] = str_replace('/', '-', $post['engaged_chidren_dob_two'] );   
               $post['engaged_chidren_dob_two'] = date('Y-m-d', strtotime($post['engaged_chidren_dob_two']));
            }else{
               $post['engaged_chidren_dob_two'] = '';
            }
            if(isset($post['engaged_chidren_dob_three']) && !empty($post['engaged_chidren_dob_three'])){
               $post['engaged_chidren_dob_three'] = str_replace('/', '-', $post['engaged_chidren_dob_three'] );   
               $post['engaged_chidren_dob_three'] = date('Y-m-d', strtotime($post['engaged_chidren_dob_three']));
            }else{
               $post['engaged_chidren_dob_three'] = '';
            }
            if(isset($post['engaged_chidren_dob_four']) && !empty($post['engaged_chidren_dob_four'])){
               $post['engaged_chidren_dob_four'] = str_replace('/', '-', $post['engaged_chidren_dob_four'] );   
               $post['engaged_chidren_dob_four'] = date('Y-m-d', strtotime($post['engaged_chidren_dob_four']));
            }else{
               $post['engaged_chidren_dob_four'] = '';
            }
            if(isset($post['engaged_chidren_dob_five']) && !empty($post['engaged_chidren_dob_five'])){
               $post['engaged_chidren_dob_five'] = str_replace('/', '-', $post['engaged_chidren_dob_five'] );   
               $post['engaged_chidren_dob_five'] = date('Y-m-d', strtotime($post['engaged_chidren_dob_five']));
            }else{
               $post['engaged_chidren_dob_five'] = '';
            }
            if(isset($post['engaged_chidren_dob_six']) && !empty($post['engaged_chidren_dob_six'])){
               $post['engaged_chidren_dob_six'] = str_replace('/', '-', $post['engaged_chidren_dob_six'] );   
               $post['engaged_chidren_dob_six'] = date('Y-m-d', strtotime($post['engaged_chidren_dob_six']));
            }else{
               $post['engaged_chidren_dob_six'] = '';
            }
             /*Single STATUS*/
            if(isset($post['chidren_dob_one']) && !empty($post['chidren_dob_one'])){
               $post['chidren_dob_one'] = str_replace('/', '-', $post['chidren_dob_one'] );   
               $post['chidren_dob_one'] = date('Y-m-d', strtotime($post['chidren_dob_one']));
            }else{
               $post['chidren_dob_one'] = '';
            }
            if(isset($post['chidren_dob_two']) && !empty($post['chidren_dob_two'])){
               $post['chidren_dob_two'] = str_replace('/', '-', $post['chidren_dob_two'] );   
               $post['chidren_dob_two'] = date('Y-m-d', strtotime($post['chidren_dob_two']));
            }else{
               $post['chidren_dob_two'] = '';
            }
            if(isset($post['chidren_dob_three']) && !empty($post['chidren_dob_three'])){
               $post['chidren_dob_three'] = str_replace('/', '-', $post['chidren_dob_three'] );   
               $post['chidren_dob_three'] = date('Y-m-d', strtotime($post['chidren_dob_three']));
            }else{
               $post['chidren_dob_three'] = '';
            }
            if(isset($post['chidren_dob_four']) && !empty($post['chidren_dob_four'])){
               $post['chidren_dob_four'] = str_replace('/', '-', $post['chidren_dob_four'] );   
               $post['chidren_dob_four'] = date('Y-m-d', strtotime($post['chidren_dob_four']));
            }else{
               $post['chidren_dob_four'] = '';
            }
            if(isset($post['chidren_dob_five']) && !empty($post['chidren_dob_five'])){
               $post['chidren_dob_five'] = str_replace('/', '-', $post['chidren_dob_five'] );   
               $post['chidren_dob_five'] = date('Y-m-d', strtotime($post['chidren_dob_five']));
            }else{
               $post['chidren_dob_five'] = '';
            }
            if(isset($post['chidren_dob_six']) && !empty($post['chidren_dob_six'])){
               $post['chidren_dob_six'] = str_replace('/', '-', $post['chidren_dob_six'] );   
               $post['chidren_dob_six'] = date('Y-m-d', strtotime($post['chidren_dob_six']));
            }else{
               $post['chidren_dob_six'] = '';
            }

            /*marketing question last added*/
            if(isset($post['purchase_date']) && !empty($post['purchase_date'])){
              $post['purchase_date'] = str_replace('/', '-', $post['purchase_date'] );   
            $post['purchase_date'] = date('Y-m-d', strtotime($post['purchase_date']));
            }else{
               $post['purchase_date'] = '';
            }

           if(isset($post['dob'])){
             $post['dob'] = str_replace('/', '-', $post['dob'] );
             $post['dob'] = date('Y-m-d', strtotime($post['dob']));
           }
           
           if(isset($post['created_at'])){
            $post['created_at']=date('Y-m-d');
           }
            
           
            $post['region'] = '';
            if(isset($post['state']) && $post['state'] != ''){
              $post['region'] =  $post['state'];
            }else{
                 $post['region'] =  $post['region'];
                
            }
            
            if(isset($post['cityinput']) && $post['cityinput'] != ''){
            $post['city'] = $post['cityinput'];
            }

            if(isset($post['state']) && $post['state'] != ''){
            $post['region'] = $post['state'];
            }else{
              $post['region'] = $post['region'];   
            }

            $regioncode = $post['region'];

            if(isset($post['region']) && $post['region'] != '' && is_numeric($post['region'])){
             
             $region_name = $this->_objectManager->get('Magento\Directory\Model\RegionFactory')->create()->load($regioncode);
             if($region_name->getData('name') == ''){
                 $post['region'] = $region_name->getData('default_name');
             }else{
                 $post['region'] = $region_name->getData('name');
             } 
              $model->setRegion($post['region']);
            }
            $model->setData($post);
            $model->save();
            $model->getId();
            $certiCode = str_pad($model->getId(), 5, "0", STR_PAD_LEFT);
            $val = $prefix . $certiCode;
            //$val = $prefix . $model->getId();
            //$model->setExpireDate($expire);
            $model->setCertificatoCode($val)->save();
            $session = $this->_objectManager->get('Magento\Customer\Model\Session');
            $session->setWarranty($val);
            $session->setBrand($cat_name);
            //$this->_view->loadLayout();
             $response = ['status' => 'true','brand'=>$post['brand'],'lang'=> $lang,'email'=> $model->getEmail(),'created_at'=>$model->getCreatedAt(),'certificato_code'=>$val ,'message' => 'You Claim has been Activated','equipment_status' => 'true'];
            //$this->_view->renderLayout();
         }
        } catch (\Exception $e) {
            $response = ['status' => 'false', 'message' => $e->getMessage()];
        }
        return $resultJson->setData($response);

    }

     public function checkEquipmentApi($code ,$mainbrand, $storename)
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
        //$equpiment = '1000000000073152256';
         /*Code to IMPORT CRM*/
        $data = [
            'login' => 'magento',
            'password' => 'arGK!eR5'
        ];
        
        $url = "https://app.damianigroup.com/wsservice/authenticate.do";
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

        if(strtolower($storename)== "italiano"){
            $language = "IT";
        }else{
            $language = "EN";
        }
        
        if(strtolower($mainbrand) == "bliss" || strtolower($mainbrand) == "calderoni"){
            $url1 = 'https://app.damianigroup.com/wsservice/services/damiani-json/execute?action=GET_MATERIAL_INFO&';
            $postData = array(
            $postCrmArray["MATERIAL_NUMBER"] = $code,
            $postCrmArray["LANGUAGE"] = $language,
            $postCrmArray["PRICE_LIST"] = "EURO_Z001",
            );
        }
        else{
            $url1 = 'https://app.damianigroup.com/wsservice/services/damiani-json/execute?action=GET_EQUIPMENT_INFO&';
                $postData = array(
            $postCrmArray["EQUIPMENT_NUMBER"] = $code,
            $postCrmArray["LANGUAGE"] = $language,
            $postCrmArray["PRICE_LIST"] = "EURO_Z001",
             );
        }
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
            
         $brand = $responses['parameter']['BRAND'];
         //echo "brand ============>".$brand."<br>";
         $priceRangeValue = $responses['parameter']['PRICE'];
         $priceRangeValue = explode(',', $priceRangeValue);
         $priceRangeValue = $priceRangeValue[0];
         $priceRangeValue =str_replace('.', '', $priceRangeValue);
         
         if(strtolower($brand) == "damiani"){
         if($priceRangeValue >= $mindamiani && $priceRangeValue < $maxdamiani ){ 
            return '{"equipment_status" : "true","message" : "Success","return":"OK"}';
           }else{
              return '{"equipment_status" : "false","message" : "Price not in range","return":"KO"}';
              }
           }
          if(strtolower($brand) == "bliss"){
           /*  $minbliss = '0';
            $maxbliss = '5000';
            $priceRangeValue = explode(',', $priceRangeValue);*/
         if($priceRangeValue >= $minbliss && $priceRangeValue < $maxbliss){ 
            return '{"equipment_status" : "true","message" : "Success","return":"OK"}';
           }else{
              return '{"equipment_status" : "false","message" : "Price not in range","return":"KO"}';
              }
           }
           if(strtolower($brand) == "rocca"){
             if($priceRangeValue >= $minrocca && $priceRangeValue < $maxrocca){ 
                return '{"equipment_status" : "true","message" : "Success","return":"OK"}';
               }else{
                return '{"equipment_status" : "false","message" : "Price not in range","return":"KO"}';
             }
           }
           if($brand == "CALDERONI_CELEBRATION"){
             if($priceRangeValue >= '0' && $priceRangeValue < '50000'){ 
                return '{"equipment_status" : "true","message" : "Success","return":"OK"}';
               }else{  
                return '{"equipment_status" : "false","message" : "Price not in range","return":"KO"}';
             }
           }
           if(strtolower($brand) == "salvini"){
         if($priceRangeValue >= $minsalvini && $priceRangeValue < $maxsalvini){ 
              return '{"equipment_status" : "true","message" : "Success","return":"OK"}';
           }else{
              return '{"equipment_status" : "false","message" : "Price not in range","return":"KO"}';
              }
           }
        }else{
            return $response;
        }
         
         //exit("SAP API die");
    
    }

}
