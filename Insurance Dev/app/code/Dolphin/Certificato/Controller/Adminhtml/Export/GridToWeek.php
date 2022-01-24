<?php
namespace Dolphin\Certificato\Controller\Adminhtml\Export;

use Magento\Ui\Component\MassAction\Filter;


class gridToWeek extends \Magento\Framework\App\Action\Action
{
    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;
    protected $filter;
    protected $request;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
         \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        Filter $filter
    ) {
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
        $this->filter = $filter;
        $this->request = $request;
        parent::__construct($context);
    }

    public function execute()
    {
        $component = $this->filter->getComponent();
        
        //echo $this->_storeManager->getStore()->getBaseUrl() . '<br />';             
        $this->_view->loadLayout(false);
        $fileName = 'certificato_weekly_report.csv';
        $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR) . "/" . $fileName;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
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

        $filterDateArray = $this->request->getParam('filters');
        
        
        //$filterDateFrom = $filterDateArray['purchase_date']['from'];
        //$filterDateTo = $filterDateArray['purchase_date']['to'];

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

        if(isset($filterDateArray['purchase_date']['from']) && isset($filterDateArray['purchase_date']['to'])){
            $filterDateFrom = date("Y-m-d", strtotime($filterDateArray['purchase_date']['from']));
            $filterDateTo = date("Y-m-d", strtotime($filterDateArray['purchase_date']['to']));
            $data->getSelect()->where("purchase_date >= '".$filterDateFrom."' AND purchase_date <= '".$filterDateTo."' ");
        }
       
       
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
                $val['doc_signedid'] =  $this->_storeManager->getStore()->getBaseUrl()."insurancepdf/document/show?certificato_code=" . $val['certificato_code'];
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

        return $this->fileFactory->create(
            $fileName,
            [
                'type' => "filename",
                'value' => $fileName,
                'rm' => true,
            ],
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
            'application/vnd.ms-excel'
        );

    }
    public function dateDiff($date1, $date2)
    {
        $date1_ts = strtotime($date1);
        $date2_ts = strtotime($date2);
        $diff = $date2_ts - $date1_ts;
        return round($diff / 86400);
    }
}
