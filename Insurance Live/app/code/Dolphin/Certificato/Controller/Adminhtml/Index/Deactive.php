<?php
/**
 * Webkul Grid List Controller.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Dolphin\Certificato\Controller\Adminhtml\Index;
 
use Magento\Framework\Controller\ResultFactory;
use \Magento\Framework\Escaper;
use \Magento\Framework\Mail\Template\TransportBuilder;


class Deactive extends \Magento\Backend\App\Action
{

    protected $date;
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;
 
    /**
     * @var \Webkul\Grid\Model\GridFactory
     */
    private $gridFactory;
 
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry,
     * @param \Webkul\Grid\Model\GridFactory $gridFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
         Escaper $_escaper,
        \Dolphin\Certificato\Model\CertificatoFactory $gridFactory,
        TransportBuilder $_transportBuilder,
       \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->gridFactory = $gridFactory;
        $this->_transportBuilder = $_transportBuilder;
        $this->date = $date;
        $this->_escaper = $_escaper;
    }
 
    /**
     * Mapped Grid List page.
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {

        $date = $this->date->date()->format('Y-m-d H:i:s'); //Deactive current date
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->gridFactory->create();

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        if ($rowId) {
           $rowData = $rowData->load($rowId);
           $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
           $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
           $customerId = $rowData->getCustomerId();
           $customer = $customerFactory->load($customerId);
           $send_mail = $customer->getEmail();
           $postData = array();
           $postData['certificato_code'] = $rowData->getCertificatoCode();
           $postData['brand'] = $rowData->getBrand();
           $postData['equpiment'] = $rowData->getEqupiment();
           $postData['model'] = $rowData->getModel();
           $postData['created_at'] = $rowData->getCreated_at();
           $rowData->setStatus('4')
            ->setDeactiveDate($date) 
            ->save();
            
           $postObject = new \Magento\Framework\DataObject();
           $postObject->setData($postData);
           $error = false;
           $sender = [
                'name' => $this->_escaper->escapeHtml("Damiani Group"),
                'email' => $this->_escaper->escapeHtml("noreply@damianigroupcustomercare.com"),
            ];

            /*Mail to Boutique / Customer for the deactivation*/
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('deactivate_insurance_notify') // this code we have mentioned in the email_templates.xml
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' => '1'
                    ]
                )
                ->setTemplateVars(array(
                    'certificato_code' => $rowData->getCertificatoCode(),
                    'brand' => $rowData->getBrand(),
                    'equpiment' => $rowData->getEqupiment(),
                    'model' => $rowData->getModel(),
                    'created_at' => $rowData->getCreated_at()
            ))
                ->setFrom($sender)
                ->addTo($send_mail)
                //->addCc("raviiozhacc@yopmail.com")
                ->getTransport();
                try{ 
                    $transport->sendMessage();
                  } catch (\Exception $e) {
                    echo $e;
             }  
             /*Mail to Boutique / Customer for the deactivation */
       }
 
       $this->coreRegistry->register('row_data', $rowData);
       //$resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
       $title = $rowId ? __('Deactive with equipment code '): __(' Insurance Data');
       //$resultPage->getConfig()->getTitle()->prepend($title);
       $this->messageManager->addSuccess(__('You have successfully deactived the warranty.'));
       $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        //$resultRedirect->getConfig()->getTitle()->prepend($title);
        $resultRedirect->setUrl($this->getUrl().'admin_r9fh5e/certificato/index/');
        return $resultRedirect;  
       //return $resultPage;
    }
 
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dolphin_Certificato::deactive');
    }
}