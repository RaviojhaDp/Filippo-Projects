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
        $result[] = [
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
            'civil status',
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
            'Wedding Annivarsery (Married)',
            'Partner Name (Married)',
            'Partner Surname (Married)',
            'Partner Birthday (Married)',
            'Number of Children (Married)',
            'Children Name (Married)',
            'Children Surname (Married)',
            'Children Birthday (Married)',
            'Children Name (Married)',
            'Children Surname (Married)',
            'Children Birthday (Married)',
            'Children Name (Married)',
            'Children Surname (Married)',
            'Children Birthday (Married)',
            'Children Name (Married)',
            'Children Surname (Married)',
            'Children Birthday (Married)',
            'Children Name (Married)',
            'Children Surname (Married)',
            'Children Birthday (Married)',
            'Children Name (Married)',
            'Children Surname (Married)',
            'Children Birthday (Married)',
            'Children Name (Engaged)',
            'Children Surname (Engaged)',
            'Children Birthday (Engaged)',
            'Number of Children (Single)',
            'Children Name (Single)',
            'Children Surname (Single)',
            'Children Birthday (Single)',
            'Children Name (Single)',
            'Children Surname (Single)',
            'Children Birthday (Single)',
            'Children Name (Single)',
            'Children Surname (Single)',
            'Children Birthday (Single)',
            'Children Name (Single)',
            'Children Surname (Single)',
            'Children Birthday (Single)',
            'Children Name (Single)',
            'Children Surname (Single)',
            'Children Birthday (Single)',
            'Children Name (Single)',
            'Children Surname (Single)',
            'Children Birthday (Single)',
            'Number of Children (Engaged)',
            'Children Name (Engaged)',
            'Children Surname (Engaged)',
            'Children Birthday (Engaged)',
            'Children Name (Engaged)',
            'Children Surname (Engaged)',
            'Children Birthday (Engaged)',
            'Children Name (Engaged)',
            'Children Surname (Engaged)',
            'Children Birthday (Engaged)',
            'Children Name (Engaged)',
            'Children Surname (Engaged)',
            'Children Birthday (Engaged)',
            'Children Name (Engaged)',
            'Children Surname (Engaged)',
            'Children Birthday (Engaged)',
            'Children Name (Engaged)',
            'Children Surname (Engaged)',
            'Children Birthday (Engaged)',
        ];
        $objDate = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime');
        $date = $objDate->gmtDate();
		
        $date1 = date('Y-m-d', strtotime($date));
        $data = $objectManager->create('Dolphin\Certificato\Model\Certificato')->getCollection()
         //->addFieldToFilter('status', array('eq' => '1'));
        ->addFieldToFilter(['status', 'status'],
                                              [
                                                  ['eq' => '1'],
                                                  ['eq' => '4'] //Deactivate
                                              ]);
        /*echo $data->getSelect();
        die;
        *///->addFieldToFilter('certificato_id',array('eq' => '55' ))
        $results = $data->getData();
        foreach ($results as $val) {
             /*if ($val['filetoupload'] != "" && $val['filetoupload'] != "undefined") {
                $val['filetoupload'] = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)."/certi_upload/" . $val['filetoupload'];
            } else {
                $val['filetoupload'] = "N/A";
            }*/
            
            if ($val['status'] == '1') {
                $val['status'] = "Active";
            } else {
                $val['status'] = "Deactive";
            }

            if(isset($val['civil_status']) && $val['civil_status'] != ''){
                if($val['civil_status'] == '1'){
                    $val['civil_status'] = "Single";
                }elseif($val['civil_status'] == '2'){
                    $val['civil_status'] = "Married";
                }elseif($val['civil_status'] == '5'){
                    $val['civil_status'] = "Engaged";
                }else{
                    $val['civil_status'] = "N/A";
                }
            }
            if ($val['privacy'] == '0') {
                $val['privacy'] = "Yes";
            } else {
                $val['privacy'] = "No";
            }

            if ($val['marketing'] == '0') {
                $val['privacy'] = "Yes";
            } else {
                $val['marketing'] = "No";
            }

            if ($val['general_conditions'] == '0') {
                $val['general_conditions'] = "Yes";
            } else {
                $val['general_conditions'] = "No";
            }

            if ($val['customer_group_id'] == '3') {
                $val['customer_group_id'] = "Retailer";
            } elseif ($val['customer_group_id'] == '4') {
                $val['customer_group_id'] = "Boutique";
            } else {
                $val['customer_group_id'] = "Client";
            }

            if ($val['doc_signedid']) {
                $val['doc_signedid'] = $this->_storeManager->getStore()->getBaseUrl()."faber/document/show?certificato_code=" . $val['certificato_code'] . "&signid=" . $val['doc_signedid'];
            }

            if (isset($val['name_boutique_retailer'])) {

                $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($val['name_boutique_retailer']);
                $customerAddress = array();
                foreach ($customerObj->getAddresses() as $address) {
                    $customerAddress[] = $address->toArray();
                }
                if (!empty($customerAddress)) {
                    $val['name_boutique_retailer'] = $customerAddress[0]['firstname'] . "," . $customerAddress[0]['city'] . "," . $customerAddress[0]['country_id'];
                    $val['add_boutique_retailer'] = $customerAddress[0]['street'];
                  }
                } else {
                    $val['name_boutique_retailer'] = "ecommerce";
                }

            $date2 = $val['created_at'];
            $monthDiff = $this->dateDiff($date2, $date1);
          //  if ($monthDiff >= '0' && $monthDiff <= '8') {
                //unset($val['civil_status']);
                unset($val['stone_code']);
                unset($val['buying_opportunity']);
                unset($val['degree_education']);
                unset($val['profession']);
                unset($val['customer_id']);
                unset($val['updated_at']);
                unset($val['sign_header_id']);
                unset($val['filetoupload']);
                if($val['partner_dob'] == '1970-01-01' || $val['chidren_dob_one'] == '1970-01-01' || $val['chidren_dob_two'] == '1970-01-01'||
                $val['chidren_dob_three'] == '1970-01-01'|| $val['chidren_dob_four'] == '1970-01-01' ||
                $val['chidren_dob_five'] == '1970-01-01' || $val['chidren_dob_six'] == '1970-01-01' || $val['partner_dob_single'] == '1970-01-01'||
                $val['first_chidren_dob_one'] == '1970-01-01' || $val['first_chidren_dob_two'] == '1970-01-01' || $val['first_chidren_dob_three'] == '1970-01-01'||
                $val['first_chidren_dob_four'] == '1970-01-01' || $val['first_chidren_dob_five'] == '1970-01-01' || $val['first_chidren_dob_six'] == '1970-01-01'||
                $val['engaged_chidren_dob_one'] == '1970-01-01' || $val['engaged_chidren_dob_two'] == '1970-01-01' || $val['engaged_chidren_dob_three'] == '1970-01-01'||
                $val['engaged_chidren_dob_four'] == '1970-01-01' || $val['engaged_chidren_dob_five'] == '1970-01-01' || $val['engaged_chidren_dob_six'] == '1970-01-01'){
                    $val['partner_dob'] = '';
                    $val['chidren_dob_one'] = '';
                    $val['chidren_dob_two'] = '';
                    $val['chidren_dob_three'] = '';
                    $val['chidren_dob_four'] = '';
                    $val['chidren_dob_five'] = '';
                    $val['chidren_dob_six'] = '';
                    $val['partner_dob_single'] = '';
                    $val['first_chidren_dob_one'] = '';
                    $val['first_chidren_dob_two'] = '';
                    $val['first_chidren_dob_three'] = '';
                    $val['first_chidren_dob_four'] = '';
                    $val['first_chidren_dob_five'] = '';
                    $val['first_chidren_dob_six'] = '';
                    $val['engaged_chidren_dob_one'] = '';
                    $val['engaged_chidren_dob_two'] = '';
                    $val['engaged_chidren_dob_three'] = '';
                    $val['engaged_chidren_dob_four'] = '';
                    $val['engaged_chidren_dob_five'] = '';
                    $val['engaged_chidren_dob_six'] = '';
                    $val['engaged_no_of_child'] = '';
                    $val['first_no_of_child'] = '';
                    $val['no_of_child'] = '';
                    
                }
                //echo "<prE>";
                //print_r($val);die;
                $result[] = $val;
           // }
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
                //->addTo("gioiellieri@funk-gruppe.it")
                //->addTo("gioiellieri@yopmail.com")
                //->addCc("ravigioiellieri@yopmail.com")
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
