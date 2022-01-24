<?php
namespace Luxmadein\Certificatodisostituzione\Controller\Index;

class CronBrowserTest extends \Magento\Framework\App\Action\Action {
	protected $_pageFactory;
	protected $cronbrowsertest;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Luxmadein\Certificatodisostituzione\Model\CronBrowserTest $cronbrowsertest) {
		$this->_pageFactory = $pageFactory;
		 $this->cronbrowsertest = $cronbrowsertest;
		return parent::__construct($context);
	}

	public function execute() {
		 $this->cronbrowsertest->getCronBrowser();
	}
}