<?php

namespace Dolphin\Certificato\Cron;

use \Psr\Log\LoggerInterface;

class Emailweeklyreport {

    protected $logger;
    private $_transportBuilder;
    protected $escaper;
    protected $directoryList;
     protected $csvProcessor;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Escaper $escaper    
     */
    public function __construct(
     \Magento\Framework\File\Csv $csvProcessor, \Magento\Framework\App\Filesystem\DirectoryList $directoryList, \Magento\Framework\Escaper $_escaper, \Magento\Framework\ObjectManagerInterface $objectmanager, \Dolphin\Certificato\Model\CertificatoFactory $certificatoModelFactory, LoggerInterface $logger, \Dolphin\Certificato\Model\Mail\TransportBuilder $_transportBuilder
    ) {
        $this->_transportBuilder = $_transportBuilder;
        $this->logger = $logger;
        $this->_escaper = $_escaper;
        $this->_objectManager = $objectmanager;
         $this->directoryList = $directoryList;
          $this->csvProcessor = $csvProcessor;
        $this->certificatoModelFactory = $certificatoModelFactory;
    }

    public function execute() {
    	/*START*/
        $path = "/var/www/html/var/Certificati.csv";
        //$fileName = "certificato_weekly_report.csv";
        $fileName = "Certificati.csv";
       //Certificati
        //Sinistri
    	//$fileName = 'certificato_weekly_report.csv';
        $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            . "/" . $fileName;  
        //$exportBlock = $this->_view->getLayout()->createBlock('Magento\Catalog\Block\Adminhtml\Product\Grid');
        //$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $base_url_config = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue("web/unsecure/base_url");
        $result = [];
        $result[] =[
            'certificato_id',
            'certificato_code',
            'expire_date',
            'customer_group_id',
            'brand',
            'name',
            'surname',
            'address',
            'zipcode',
            'city',
            'region',
            'phone',
            'mobile_phone',
            'fiscal_code',
            'dob',
            'gender',
            'equpiment',
            'model',
            'serial_number',
            'name_boutique_retailer',
            'add_boutique_retailer',
            'seller_name',
            'purchase_date',
            'general_conditions',
            'privacy',
            'marketing',
            'profiling',
            'cession',
            'status',
            'created_at',
            //'filetoupload',
            'email',
            'country_id',
            'receipt_number',
            'Sign Document',
            'Wedding Annivarsery',  
            'Partner Name', 
            'Partner Surname',  
            'Partner Birthday', 
            'Number of Children',   
            'Children Name',    
            'Children Surname', 
            'Children Birthday',    
            'Children Name',    
            'Children Surname', 
            'Children Birthday',    
            'Children Name',    
            'Children Surname', 
            'Children Birthday',    
            'Children Name',    
            'Children Surname', 
            'Children Birthday',    
            'Children Name',    
            'Children Surname', 
            'Children Birthday',    
            'Children Name',    
            'Children Surname', 
            'Children Birthday'

        ];
        $objDate = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime');
        $date = $objDate->gmtDate();
		
        $date1 = date('Y-m-d', strtotime($date));
        $data = $objectManager->create('Dolphin\Certificato\Model\Certificato')->getCollection()
        ->addFieldToFilter('status',array('eq' => '1' ));
        /*echo $data->getSelect();
        die;
        *///->addFieldToFilter('certificato_id',array('eq' => '55' ))
        $results = $data->getData();
        foreach ($results as $val) {

            if($val['filetoupload'] != "" && $val['filetoupload'] != "undefined"){
            $val['filetoupload']  = $base_url_config."media/certi_upload/".$val['filetoupload'];
           }else{
            $val['filetoupload']  = "N/A";
           }

            if($val['status'] == '1'){
        $val['status'] = "True";
            }else{
                $val['status'] = "False";
            }
            
            if($val['privacy'] == '0'){
        $val['privacy'] = "Yes";
            }else{
                $val['privacy'] = "No";
            }

            if($val['marketing'] == '0'){
        $val['privacy'] = "Yes";
            }else{
                $val['marketing'] = "No";
            }

             if($val['general_conditions'] == '0'){
        $val['general_conditions'] = "Yes";
            }else{
                $val['general_conditions'] = "No";
            }


            if($val['customer_group_id'] == '3'){
                $val['customer_group_id'] = "Retailer";
             }elseif ($val['customer_group_id'] == '4') {
                $val['customer_group_id'] = "Boutique";
             }else{
                $val['customer_group_id'] = "Client";
             }

            if($val['doc_signedid']){
                $val['doc_signedid'] = $base_url_config."/it/faber/document/show?certificato_code=".$val['certificato_code']."&signid=". $val['doc_signedid'];
            }


            /*if($val['civil_status'] == '1'){
            $val['civil_status'] = "Single";
            }elseif($val['civil_status'] == '2'){
            $val['civil_status'] = "Married";
            }
            elseif($val['civil_status'] == '3'){
            $val['civil_status'] = "Divorced";
            }
            elseif($val['civil_status'] == '4'){
            $val['civil_status'] = "Widower";
            }
            else{
            $val['civil_status'] = "N/A";
            }

            if($val['degree_education'] == '1'){
            $val['degree_education'] = "O-levels/Junior High Diploms";
            }elseif($val['degree_education'] == '2'){
            $val['degree_education'] = "A-levels/Higher High Diploms";
            }
            elseif($val['degree_education'] == '3'){
            $val['degree_education'] = "University Pass Degree";
            }
            elseif($val['degree_education'] == '4'){
            $val['degree_education'] = "University Honours Degree";
            }
            else{
            $val['degree_education'] = "N/A";
            }

            if($val['profession'] == '1'){
            $val['profession'] = "Entrepreneur";
            }elseif($val['profession'] == '2'){
            $val['profession'] = "Self-employed";
            }
            elseif($val['profession'] == '3'){
            $val['profession'] = "Manager";
            }
            elseif($val['profession'] == '4'){
            $val['profession'] = "Employee";
            }
            elseif($val['profession'] == '5'){
            $val['profession'] = "Worker";
            }
            elseif($val['profession'] == '6'){
            $val['profession'] = "Other";
            }
            else{
            $val['profession'] = "N/A";
            }


            if($val['buying_opportunity'] == '1'){
            $val['buying_opportunity'] = "Anniversary";
            }elseif($val['buying_opportunity'] == '2'){
            $val['buying_opportunity'] = "Birthday";
            }
            elseif($val['buying_opportunity'] == '3'){
            $val['buying_opportunity'] = "Engagement";
            }
            elseif($val['buying_opportunity'] == '4'){
            $val['buying_opportunity'] = "Wedding";
            }
            elseif($val['buying_opportunity'] == '5'){
            $val['buying_opportunity'] = "Birth";
            }
            elseif($val['buying_opportunity'] == '6'){
            $val['buying_opportunity'] = "Christmas";
            }
            elseif($val['buying_opportunity'] == '7'){
            $val['buying_opportunity'] = "Other";
            }
            else{
            $val['buying_opportunity'] = "N/A";
            }
            

            if($val['reason_purchase'] == '1'){
            $val['reason_purchase'] = "For yourself";
            }elseif($val['reason_purchase'] == '2'){
            $val['reason_purchase'] = "Gift";
            }
            else{
            $val['reason_purchase'] = "N/A";
            }


            if($val['reason_choice'] == '1'){
            $val['reason_choice'] = "Brand";
            }elseif($val['reason_choice'] == '2'){
            $val['reason_choice'] = "Design";
            }
            elseif($val['reason_choice'] == '3'){
            $val['reason_choice'] = "Jewellers recommendation";
            }
            elseif($val['reason_choice'] == '4'){
            $val['reason_choice'] = "Product guarantee";
            }
            elseif($val['reason_choice'] == '5'){
            $val['reason_choice'] = "Other";
            }
            else{
            $val['reason_choice'] = "N/A";
            }



            if($val['came_to_know'] == '1'){
            $val['came_to_know'] = "Brochure";
            }elseif($val['came_to_know'] == '2'){
            $val['came_to_know'] = "Jewellers display";
            }
            elseif($val['came_to_know'] == '3'){
            $val['came_to_know'] = "Advertising";
            }
            elseif($val['came_to_know'] == '4'){
            $val['came_to_know'] = "Worn by acquaintaces / celebrities";
            }
            elseif($val['came_to_know'] == '5'){
            $val['came_to_know'] = "Internet";
            }
            elseif($val['came_to_know'] == '6'){
            $val['came_to_know'] = "Social media";
            }
            elseif($val['came_to_know'] == '7'){
            $val['came_to_know'] = "Other";
            }
            else{
            $val['came_to_know'] = "N/A";
            }
*/

            if(isset($val['name_boutique_retailer'])){

                $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($val['name_boutique_retailer']);
                 $customerAddress = array();
                foreach ($customerObj->getAddresses() as $address) {
                    $customerAddress[] = $address->toArray();
                }
               if(!empty($customerAddress)){
               /*   echo "<pre>";
                print_r($customerAddress[0]);
                die;*/
              $val['name_boutique_retailer'] =  $customerAddress[0]['firstname'] .",". $customerAddress[0]['city'] .",". $customerAddress[0]['country_id'];
             $val['add_boutique_retailer']=  $customerAddress[0]['street'];
                }
            }else{
                $val['name_boutique_retailer'] =  "ecommerce";  
            }

           


             $date2 = $val['created_at'];
             $monthDiff = $this->dateDiff($date2, $date1);
             if ($monthDiff >= '0' && $monthDiff <= '8' ) {
                unset($val['civil_status']);
                unset($val['buying_opportunity']);
                unset($val['degree_education']);
                unset($val['profession']);
                unset($val['reason_purchase']);
                unset($val['reason_choice']);
                unset($val['came_to_know']);
                unset($val['customer_id']);
                unset($val['updated_at']);
                unset($val['sign_header_id']);  
                unset($val['filetoupload']);
             $result[] = $val;
          }
        }
    
        $this->csvProcessor
            //->setDelimiter(';')
            //->setEnclosure('"')
            ->saveData(
                $filePath,
                $result
            );

       
    	/*END*/

        

        $postObject = new \Magento\Framework\DataObject();

            $postObject->setData($val);
            
            $error = false;

            $sender = [
                'name' => $this->_escaper->escapeHtml("Damiani Group"),
                'email' => $this->_escaper->escapeHtml("noreply@damianigroupcustomercare.com"),
            ];

            

            $transport = $this->_transportBuilder
                //->setTemplateIdentifier('email_template_register_success') // this code we have mentioned in the email_templates.xml
                  ->setTemplateIdentifier('dolphin_certificato_weekly_report_cron') 
               ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' => 1,
                    ]
                )
                ->setTemplateVars(['customer' => $postObject])
                ->setFrom($sender)
                ->addTo("gioiellieri@funk-gruppe.it")
           		->addCc("r.trombini@funk-gruppe.it")
                //->addTo("filippomaria.capitanio@luxmadein.com")
                ->addAttachment($path, $fileName)  
                ->getTransport();
                 $transport->sendMessage();
                try{  
                //$transport->sendMessage();
              } catch (\Exception $e) {
            return $response = ['status' => 'false', 'message' => $e->getMessage()];
         }
       
    }
    public function dateDiff($date1, $date2) {
            $date1_ts = strtotime($date1);
            $date2_ts = strtotime($date2);
            $diff = $date2_ts - $date1_ts;
            return round($diff / 86400);
        }
    
}
