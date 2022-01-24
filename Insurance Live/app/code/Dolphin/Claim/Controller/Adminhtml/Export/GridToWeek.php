<?php
namespace Dolphin\Claim\Controller\Adminhtml\Export;

class gridToWeek extends \Magento\Framework\App\Action\Action
{
    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_view->loadLayout(false);
        $fileName = 'claim_weekly_report.csv';
        $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            . "/" . $fileName;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $result = [];
        $result[] = [
            'claim_id',
            'certificato_code',
            'certi_create_at',
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
            'email',
            'equipment',
            'model',
            'serial_number',
            'name_boutique_retailer',
            'address_boutique_retailer',
            'purchase_date',
            'Data Denuncia',
            'authorty',
            'location_authority',
            'document_number',
            'describe_events',
            'complaint',
            'damiani_spa',
            'authenticity',
            'country_id',
            'casualty_date',
            'casualty_country',
            'casualty_address',
            'casualty_region',
            'casualty_city',
            'casualty_zipcode',
            'created_at',
            'status',
            'Sign Document',

        ];
        $objDate = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime');
        $date = $objDate->gmtDate();
        $date1 = date('Y-m-d', strtotime($date));
        $data = $objectManager->create('Dolphin\Claim\Model\Claim')->getCollection()
            ->addFieldToFilter('status_claim', array('eq' => '1'))->setOrder('claim_id', 'DESC');
        $results = $data->getData();
        foreach ($results as $val) {

            if ($val['status_claim'] == '1') {
                $val['status_claim'] = "True";
            } else {
                $val['status_claim'] = "False";
            }

            if ($val['customer_group_id'] == '3') {
                $val['customer_group_id'] == "Retailer";
            } elseif ($val['customer_group_id'] == '4') {
                $val['customer_group_id'] == "Boutique";
            } else {
                $val['customer_group_id'] == "Client";
            }

            //if ($val['doc_signedid']) {

                $prefix = '';
                if (strtolower($val['brand']) == "damiani") {
                    $prefix = "D";
                }
                if (strtolower($val['brand']) == "salvini") {
                    $prefix = "S";
                }
                if (strtolower($val['brand']) == "rocca") {
                    $prefix = "R";
                }
                if (strtolower($val['brand']) == "bliss") {
                    $prefix = "B";
                }
                if (strtolower($val['brand']) == "calderoni") {
                    $prefix = "C";
                }

                $certificato_code = $prefix . $val['certificato_id'];

                $val['doc_signedid'] = $this->_storeManager->getStore()->getBaseUrl()."insurancepdf/document/showclaim?certificato_code=" . $certificato_code;
            //}

            if (isset($val['name_boutique_retailer'])) {

                $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($val['name_boutique_retailer']);
                $customerAddress = array();
                foreach ($customerObj->getAddresses() as $address) {
                    $customerAddress[] = $address->toArray();
                }
                if (!empty($customerAddress)) {
                    $val['name_boutique_retailer'] = $customerAddress[0]['firstname'] . "," . $customerAddress[0]['city'] . "," . $customerAddress[0]['country_id'];

                }
            }
            if ($val['complaint'] != "") {
                $val['complaint'] = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)."complaint/" . $val['complaint'];
            }
            if ($val['damiani_spa'] != "") {
                $val['damiani_spa'] = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)."damiani_spa/" . $val['damiani_spa'];
            }
            if ($val['authenticity'] != "") {
                $val['authenticity'] = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)."authenticity/" . $val['authenticity'];
            }
            $date2 = $val['created_at'];
            $monthDiff = $this->dateDiff($date2, $date1);
            if ($monthDiff >= '0' && $monthDiff <= '50') {
                
                if ($val['certificato_id'] != "") {
                    $val['certificato_id'] = $certificato_code;
                }

                $val = array_slice($val, 0, 3, true) +
                array("expire_date" => $val['expire_date']) +
                array_slice($val, 3, count($val) - 1, true);
                unset($val['customer_id']);
				
				if($val['date_of_termination'] == '1970-01-01'){
					$val['date_of_termination'] = '';
				}
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

        return $this->fileFactory->create(
            $fileName,
            [
                'type' => "filename",
                'value' => $fileName,
                'rm' => true,
            ],
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
            'application/octet-stream'
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
