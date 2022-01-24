<?php
namespace Dolphin\InsurancePdf\Controller\Document;
use Magento\Framework\App\Action\Context;
class Showclaim extends \Magento\Framework\App\Action\Action
{
	protected $faberHelper;
	/**
     * @var Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_downloader;
 
    /**
     * @var Magento\Framework\Filesystem\DirectoryList
     */
    protected $_directory;
 
	
	public function __construct(
		Context $context,
		\Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem\DirectoryList $directory,
		\Dolphin\InsurancePdf\Helper\Data $faberHelper
	) {
		
		$this->_downloader =  $fileFactory;
        $this->directory = $directory;
		$this->faberHelper = $faberHelper;
		parent::__construct($context);
	}

    public function execute()
    {
    	
		//$order = $this->orderRepository->get($this->getRequest()->getParam("id"));
		if(isset($_GET['certificato_code'])){
		$certificato_code = $_GET['certificato_code'];
    	
		if($certificato_code){
		$fileName = $certificato_code.".pdf";
        $file = $this->directory->getPath("media")."/Signed_Document/Claim/".$fileName;
        // do your validations
 	
        /**
         * do file download
         */
        return $this->_downloader->create(
            $fileName,
            @file_get_contents($file) );
		}
		else{
			echo __("No document found");
			exit;
		}
	}
	else{
			echo __("No document found");
			exit;
		}
	}
}