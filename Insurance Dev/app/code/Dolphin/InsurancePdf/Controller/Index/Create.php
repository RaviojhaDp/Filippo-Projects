<?php
namespace Dolphin\InsurancePdf\Controller\Index;

class Create extends \Magento\Framework\App\Action\Action
{
	protected $resultJsonFactory;
	protected $insurancepdf;
	protected $faberHelper;
	
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Framework\ObjectManagerInterface $objectmanager,
		\Dolphin\InsurancePdf\Model\Api $insurancepdf,
		\Dolphin\InsurancePdf\Helper\Data $faberHelper
	) {
		parent::__construct($context);
		$this->_objectManager = $objectmanager;
		$this->resultJsonFactory = $resultJsonFactory;
		$this->insurancepdf = $insurancepdf;
		$this->faberHelper = $faberHelper;
	}

	public function execute()
	{

		
		$this->insurancepdf->postToInsurancePdf();

		/*$headerFields = array(
			"namea" => $this->getRequest()->getPost("name"),
			"surna" => $this->getRequest()->getPost("surname"),
			"dobia" => $this->getRequest()->getPost("dob"),
			"addra" => $this->getRequest()->getPost("address"),
			"capra" => $this->getRequest()->getPost("zipcode"),
			"citra" => ucwords(strtolower($city)),
			"prora" => "-",
			"nazra" => $nazra,
			"telra" => $this->getRequest()->getPost("mobile_phone"),
			"emara" => $this->getRequest()->getPost("email"),
			"cfisa" => $fiscal_code_val,
			"equipm" => $equpiment,
			"codmat" => $serial_number,
			"libero1" => "Test",
			//"sernum" => $this->getRequest()->getPost("serial_number"),
			//"datatt" => $this->getRequest()->getPost("created_at"),
			"datatt" => $date[0],
			"numcer" => $this->getRequest()->getPost("certificato_code"),
			"Template_lkp" =>  $tempID // You can use template 4 that it’s the only that works for now
						); */

		
		

			
			
			//$result = $this->resultJsonFactory->create();
			//return $result->setData($response);

		}
		
	}