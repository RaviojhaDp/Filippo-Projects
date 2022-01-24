<?php

namespace Dolphin\Certificato\Cron;

use \Psr\Log\LoggerInterface;

class Emailclaimweeklyreport
{

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
        \Magento\Framework\File\Csv $csvProcessor, \Magento\Framework\App\Filesystem\DirectoryList $directoryList, \Magento\Framework\Escaper $_escaper, \Magento\Framework\ObjectManagerInterface $objectmanager, \Dolphin\Claim\Model\ClaimFactory $certificatoModelFactory, LoggerInterface $logger, \Dolphin\Certificato\Model\Mail\TransportBuilder $_transportBuilder
    ) {
        $this->_transportBuilder = $_transportBuilder;
        $this->logger = $logger;
        $this->_escaper = $_escaper;
        $this->_objectManager = $objectmanager;
        $this->directoryList = $directoryList;
        $this->csvProcessor = $csvProcessor;
        $this->certificatoModelFactory = $certificatoModelFactory;
    }

    public function execute()
    {
        /*START*/
        $fileName = 'Sinistri.csv';
        $path = "/var/www/html/var/Sinistri.csv";
        $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            . "/" . $fileName;

        // $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
        //    . "/" . $fileName;
        //$exportBlock = $this->_view->getLayout()->createBlock('Magento\Catalog\Block\Adminhtml\Product\Grid');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $base_url_config = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue("web/unsecure/base_url");
        $result = [];
        $result[] = [
            'claim_id',
            'certificato_id',
            'certi_create_at',
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
            'termination_date',
            'authorty',
            'location_authority',
            'document_number',
            'describe_events',
            'complaint',
            'damiani_spa',
            'authenticity',
            'country_id',
            'expire_date',
            'customer_id',
            'casualty_date',
            'casualty_country',
            'casualty_address',
            'casualty_region',
            'casualty_city',
            'casualty_zipcode',
            'created_at',
            'status',
            'Sign Document',
            'certificato_code',
        ];
        $objDate = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime');
        $date = $objDate->gmtDate();
        $date1 = date('Y-m-d', strtotime($date));
        $data = $objectManager->create('Dolphin\Claim\Model\Claim')->getCollection()
            ->addFieldToFilter('status_claim', array('eq' => '1'))->setOrder('claim_id', 'DESC');
        //->addFieldToFilter('certificato_id',array('eq' => '55' ))
        $results = $data->getData();
        foreach ($results as $val) {
            if ($val['status_claim'] == '1') {
                $val['status_claim'] = "True";
            } else {
                $val['status_claim'] = "False";
            }

            /*if($val['privacy'] == '0'){
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
             */
            $val['logo_url'] = $base_url_config."/media/logo-dgroup.png";

            if ($val['customer_group_id'] == '3') {
                $val['customer_group_id'] = "Retailer";
            } elseif ($val['customer_group_id'] == '4') {
                $val['customer_group_id'] = "Boutique";
            } else {
                $val['customer_group_id'] = "Client";
            }

            if ($val['doc_signedid']) {

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

                $val['doc_signedid'] = $base_url_config."it/faber/document/show?certificato_code=" . $certificato_code . "&signid=" . $val['doc_signedid'];
            }

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
                $val['complaint'] = $base_url_config."media/complaint/" . $val['complaint'];
            }
            if ($val['damiani_spa'] != "") {
                $val['damiani_spa'] = $base_url_config."media/damiani_spa/" . $val['damiani_spa'];
            }
            if ($val['authenticity'] != "") {
                $val['authenticity'] = $base_url_config."media/authenticity/" . $val['authenticity'];
            }

            $val['certficate_code'] = $certificato_code;
            $date2 = $val['purchase_date'];
            $monthDiff = $this->dateDiff($date2, $date1);
            if ($monthDiff >= '0' && $monthDiff <= '8') {
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
        try {
            //$transport->sendMessage();
        } catch (\Exception $e) {
            return $response = ['status' => 'false', 'message' => $e->getMessage()];
        }

    }
    public function dateDiff($date1, $date2)
    {
        $date1_ts = strtotime($date1);
        $date2_ts = strtotime($date2);
        $diff = $date2_ts - $date1_ts;
        return round($diff / 86400);
    }

}
