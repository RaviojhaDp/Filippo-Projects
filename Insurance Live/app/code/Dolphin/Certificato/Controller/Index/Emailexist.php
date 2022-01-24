<?php

namespace Dolphin\Certificato\Controller\Index;

use Magento\Framework\App\Action\Context;
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

class Emailexist extends \Magento\Framework\App\Action\Action
{ 
    protected $_escaper;
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
        Context $context,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        AddressFactory $addressFactory,
        ManagerInterface $messageManager,
        Escaper $escaper,
        UrlFactory $urlFactory,
        Session $session,
        TransportBuilder $_transportBuilder,
        Escaper $_escaper,
        Validator $formKeyValidator = null
    )
    {
        $this->_transportBuilder = $_transportBuilder;
        $this->storeManager     = $storeManager;
        $this->customerFactory  = $customerFactory;
        $this->addressFactory   = $addressFactory;
        $this->messageManager   = $messageManager;
        $this->escaper          = $escaper;
        $this->urlModel         = $urlFactory->create();
        $this->session          = $session;
        $this->_escaper         = $_escaper;
        $this->formKeyValidator = $formKeyValidator ?: ObjectManager::getInstance()->get(Validator::class);
        parent::__construct($context);
    }

    public function execute()
    {
        $email = $this->getRequest()->getPost('data');
        $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        if ($customer->loadByEmail($email)->getId()) {
            echo '{"data":true}';
        } else{
         echo '{"data":false}';
      }
    }  
  }
  
        
