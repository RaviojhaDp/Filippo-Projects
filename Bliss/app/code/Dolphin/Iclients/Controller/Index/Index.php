<?php
/**
 * Copyright © 2015 DolphinWebSolution. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dolphin\Iclients\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * @var PageFactory
	 */
	protected $resultPageFactory;

	/**
	 * @var ForwardFactory
	 */
	protected $resultForwardFactory;

	/**
	 * @var \Dolphin\Iclients\Helper\Data
	 */
	protected $helper;

	/**
	 * Index constructor.
	 *
	 * @param \Magento\Framework\App\Action\Context $context
	 * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
	 * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
	 * @param \Dolphin\Iclients\Helper\Data $helper
	 */
	public function __construct(
		Context $context,
		PageFactory $resultPageFactory,
		ForwardFactory $resultForwardFactory,
		\Dolphin\Iclients\Helper\Data $helper
	) {
		$this->resultPageFactory = $resultPageFactory;
		$this->resultForwardFactory = $resultForwardFactory;
		$this->helper = $helper;
		parent::__construct($context);
	}

	/**
	 * Dispatch request
	 *
	 * @param RequestInterface $request
	 * @return \Magento\Framework\App\ResponseInterface
	 * @throws \Magento\Framework\Exception\NotFoundException
	 */
	public function dispatch(RequestInterface $request) {
		if (!$this->helper->isEnabled()) {
			throw new NotFoundException(__('Page not found.'));
		}
		return parent::dispatch($request);
	}

	/**
	 * Dolphin Iclients Page
	 *
	 * @return \Magento\Framework\View\Result\Page
	 */
	public function execute() {
		/** @var \Magento\Framework\View\Result\Page $resultPage */
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->set(__('Dolphin Iclients'));
		if (!$resultPage) {
			$resultForward = $this->resultForwardFactory->create();
			return $resultForward->forward('noroute');
		}
		return $resultPage;
	}
}