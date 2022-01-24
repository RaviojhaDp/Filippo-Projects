<?php
namespace Dolphin\InsurancePdf\Controller\Index;

use Magento\Framework\Controller\ResultFactory; 
use \Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Escaper;
use Magento\Store\Model\StoreManagerInterface;

class email extends \Magento\Framework\App\Action\Action
{
    protected $storeManager;
	protected $_pageFactory;
	protected $request;
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
        StoreManagerInterface $storeManager,
         \Magento\Framework\App\Request\Http $request,
		\Magento\Framework\View\Result\PageFactory $pageFactory, TransportBuilder $_transportBuilder, Escaper $_escaper)
	{
		$this->_pageFactory = $pageFactory;
        $this->storeManager = $storeManager;
        $this->request = $request;
		 $this->_escaper = $_escaper;
		 $this->_transportBuilder = $_transportBuilder;
		return parent::__construct($context);
	}

	public function execute()
	{	
        $post_url = $this->request->getParam('url');
		$certificato_code = $this->request->getParam('certificato_code');
        $lang = $this->request->getParam('lang');
        $email = $this->request->getParam('email');
		$postObject = new \Magento\Framework\DataObject();
        $postObject->setData(array("certificato_code" => $certificato_code,"lang" => $lang,"url" => $post_url));
        $error = false;
        $sender = [
              'name' => $this->_escaper->escapeHtml("Damiani Group"),
              'email' => $this->_escaper->escapeHtml("noreply@damianigroupcustomercare.com"),
        ];
          
        //$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE; 
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier('email_template_insurance_confirm') // this code we have mentioned in the email_templates.xml
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                    'store' => $lang,
                ]
            )
            ->setTemplateVars(['customer' => $postObject])
            ->setFrom($sender)
            ->addTo($email)
            //->addTo("divyaraj1@yopmail.com")
            // ->addAttachment($path, $fileName)  
            ->getTransport();
            //$transport->sendMessage();
            try{ 
               $transport->sendMessage();
            } catch (\Exception $e) {
            return $response = ['status' => 'false', 'message' => $e->getMessage()];
         }
     }
}