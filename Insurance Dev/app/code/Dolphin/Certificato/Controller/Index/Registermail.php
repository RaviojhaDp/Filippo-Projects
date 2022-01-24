<?php

/**
 * Created by wilson.sun330@gmail.com
 * Date: 13/05/2015
 * Date: 13/05/2015
 * Time: 5:02 PM
 */

namespace Dolphin\Certificato\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\AddressFactory;
use Magento\Framework\Message\ManagerInterface;
//use Magento\Framework\Escaper;
use Magento\Framework\UrlFactory;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Registration;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\App\ObjectManager;
use \Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Escaper;

class Registermail extends \Magento\Framework\App\Action\Action {

    protected $_escaper;
    protected $resultJsonFactory;
    protected $_logger;
    protected $_storeManager;
    private $_transportBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Customer\Model\AddressFactory
     */
    protected $addressFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Framework\UrlFactory
     */
    protected $urlFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;

    /**
     * @var Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;
    
    protected $inlineTranslation;
    protected $scopeConfig;
    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CustomerFactory $customerFactory
     * @param AddressFactory $addressFactory
     * @param ManagerInterface $messageManager
     * @param Escaper $escaper
     * @param UrlFactory $urlFactory
     * @param Session $session
     * @param Validator $formKeyValidator
     */
    public function __construct(
    Context $context, StoreManagerInterface $storeManager, CustomerFactory $customerFactory, AddressFactory $addressFactory, ManagerInterface $messageManager, Escaper $escaper, UrlFactory $urlFactory, Session $session, TransportBuilder $_transportBuilder, Escaper $_escaper, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,JsonFactory $resultJsonFactory,Validator $formKeyValidator = null
    ) {
        $this->_transportBuilder = $_transportBuilder;
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->addressFactory = $addressFactory;
        $this->messageManager = $messageManager;
        $this->escaper = $escaper;
        $this->urlModel = $urlFactory->create();
        $this->session = $session;
        $this->_escaper = $_escaper;
        $this->inlineTranslation = $inlineTranslation;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->scopeConfig = $scopeConfig;
        $this->formKeyValidator = $formKeyValidator ?: ObjectManager::getInstance()->get(Validator::class);

        // messageManager can also be set via $context
        // $this->messageManager   = $context->getMessageManager();

        parent::__construct($context);
    }

  

    public function execute() {
    
            $post =  $this->getRequest()->getPost();
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);
            $error = false;
            $sender = [
                'name' => $this->_escaper->escapeHtml("Damiani Group"),
                'email' => $this->_escaper->escapeHtml("noreply@damianigroupcustomercare.com"),
            ];

  
            if(strtolower($STORE) == "english"){
            	$storeScope = '2';
            }else{
            	$storeScope = '1';
            }

            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('dolphin_certificato_template') // this code we have mentioned in the email_templates.xml
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' => $storeScope,
                    ]
                )
                ->setTemplateVars(['customer' => $postObject])
                ->setFrom($sender)
                ->addTo($post["email"])
                ->getTransport();
                 //$transport->sendMessage();
                try{ 
                $transport->sendMessage();
              } catch (\Exception $e) {
            $response = ['status' => 'false', 'message' => $e->getMessage()];
         }
         
            $response1 = $customer->loadByEmail($email)->getId();
            $response3 = $customer->loadByEmail($email)->getGroupId();
             $response2 = ['status' => 'new', 'id' => $response1,'group'=>$response3];
            return $resultJson->setData($response2);
        }
      
    

    public function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%&';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
