<?php
/**
 * Created by wilson.sun330@gmail.com
 * Date: 13/05/2015
 * Date: 13/05/2015
 * Time: 5:02 PM
 */
namespace Dolphin\Insurance\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\AddressFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Escaper;
use Magento\Framework\UrlFactory;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Registration;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\App\ObjectManager;

class ExtendCreate extends \Magento\Framework\App\Action\Action
{   
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
        Validator $formKeyValidator = null
    )
    {
        $this->storeManager     = $storeManager;
        $this->customerFactory  = $customerFactory;
        $this->addressFactory   = $addressFactory;
        $this->messageManager   = $messageManager;
        $this->escaper          = $escaper;
        $this->urlModel         = $urlFactory->create();
        $this->session          = $session;
        $this->formKeyValidator = $formKeyValidator ?: ObjectManager::getInstance()->get(Validator::class);
        
        // messageManager can also be set via $context
        // $this->messageManager   = $context->getMessageManager();
 
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
         if ($this->session->isLoggedIn()) {
             $resultRedirect->setPath('/');
           return $resultRedirect;
        }
        
        // check if the form is actually posted and has the proper form key
 
        $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();
        
        $group_id = $this->getRequest()->getPost('group_id');
        $firstName = $this->getRequest()->getPost('firstname');
        $lastName = $this->getRequest()->getPost('lastname');
        $email = $this->getRequest()->getPost('email');
        $password = $this->getRequest()->getPost('password');
        $company = $this->getRequest()->getPost('company');
        $country_id = $this->getRequest()->getPost('country_id');
        $telephone = $this->getRequest()->getPost('telephone');
        $street = $this->getRequest()->getPost('street');
        $city = $this->getRequest()->getPost('city');
        $postcode = $this->getRequest()->getPost('postcode');
        /*if($group_id == '4'){
        $password = $this->generateRandomString(8); 
        }*/
 
        // instantiate customer object
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        
        // check if customer is already present
        // if customer is already present, then show error message
        // else create new customer
        if ($customer->loadByEmail($email)->getId()) {
            //echo 'Customer with the email ' . $email . ' is already registered.';
            $message = __(
                'There is already an account with this email address "%1".',
                $email
            );
            // @codingStandardsIgnoreEnd
            $this->messageManager->addError($message);
        } else {
            
            try {
                 if($group_id == '4'){
                 $customer->setCompany("test");
                 }
                 
                $customer->setEmail($email); 
                /*if($lastName == ''){
                    $lastName = "Test";
                }*/
                if($firstName == ''){
                    $firstName = $company;
                }
                $customer->setFirstname($firstName);
                $customer->setLastname($lastName);
                $customer->setGroupId($group_id);
                // set null to auto-generate password
                $customer->setPassword($password); 
 
                // set the customer as confirmed
                // this is optional
                // comment out this line if you want to send confirmation email
                // to customer before finalizing his/her account creation
                //$customer->setForceConfirmed(true);
                
                // save data
                $customer->save();
                 $this->session->setCustomerAsLoggedIn($customer);
                if($group_id == '3' || $group_id == '4'){
                // save customer address
                // this is optional
                // you can skip saving customer address while creating the customer
                $customerAddress = $this->addressFactory->create();                
                $customerAddress->setCustomerId($customer->getId())
                                ->setFirstname($firstName)
                                ->setLastname($lastName)
                                ->setCountryId($country_id)
                                //->setRegionId('12') 
                                //->setRegion('California') 
                                ->setPostcode($postcode)
                                ->setCity($city)
                                ->setTelephone($telephone)
                                //->setFax('999')
                                ->setCompany($company)
                                ->setStreet(array(
                                    '0' => $street[0] // compulsory
                              
                                ))   
                                ->setIsDefaultBilling('1')
                                ->setIsDefaultShipping('1')
                                ->setSaveInAddressBook('1');
            
            try {
                    // save customer address
                        $customerAddress->save();
                } catch (Exception $e) {
                    $this->messageManager->addException($e, __('We can\'t save the customer address.'));               
                    }
                }
                // send welcome email to the customer
                $customer->sendNewAccountEmail();
 
                //echo 'Customer with the email ' . $email . ' is successfully created.';
 
                $this->messageManager->addSuccess(
                    __(
                        'Customer account with email %1 created successfully.',
                        $email
                    )
                );
                
                
                
                $url = $this->urlModel->getUrl('/', ['_secure' => true]);
                $resultRedirect->setUrl($this->_redirect->success($url));
                
                //$resultRedirect->setPath('*/*/');
                return $resultRedirect;
            } catch (StateException $e) {
                $url = $this->urlModel->getUrl('customer/account/forgotpassword');
                // @codingStandardsIgnoreStart
                $message = __(
                    'There is already an account with this email address. If you are sure that it is your email address, <a href="%1">click here</a> to get your password and access your account.',
                    $url
                );
                // @codingStandardsIgnoreEnd
                $this->messageManager->addError($message);
            } catch (InputException $e) {
                $this->messageManager->addError($this->escaper->escapeHtml($e->getMessage()));
                foreach ($e->getErrors() as $error) {
                    $this->messageManager->addError($this->escaper->escapeHtml($error->getMessage()));
                }
            } catch (LocalizedException $e) {
                $this->messageManager->addError($this->escaper->escapeHtml($e->getMessage()));
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('We can\'t save the customer.'));
            }
        }
 
        $this->session->setCustomerFormData($this->getRequest()->getPostValue());
        $defaultUrl = $this->urlModel->getUrl('*/*/', ['_secure' => true]);
        $resultRedirect->setUrl($this->_redirect->error($defaultUrl));
        return $resultRedirect;
    }
    
        
    public function generateRandomString($length) {
        $characters = 
       '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%&';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    } 
}