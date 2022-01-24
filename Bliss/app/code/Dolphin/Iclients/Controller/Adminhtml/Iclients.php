<?php
/**
 * A Magento 2 module named Dolphin/Iclients
 * Copyright (C) 2019
 *
 * This file included in Dolphin/Iclients is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Dolphin\Iclients\Controller\Adminhtml;

abstract class Iclients extends \Magento\Backend\App\Action {

	const ADMIN_RESOURCE = 'Dolphin_Iclients::top_level';
	protected $_coreRegistry;

	/**
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Magento\Framework\Registry $coreRegistry
	 */
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\Registry $coreRegistry
	) {
		$this->_coreRegistry = $coreRegistry;
		parent::__construct($context);
	}

	/**
	 * Init page
	 *
	 * @param \Magento\Backend\Model\View\Result\Page $resultPage
	 * @return \Magento\Backend\Model\View\Result\Page
	 */
	public function initPage($resultPage) {
		$resultPage->setActiveMenu(self::ADMIN_RESOURCE)
			->addBreadcrumb(__('Dolphin'), __('Dolphin'))
			->addBreadcrumb(__('E-corner'), __('E-corner'));
		return $resultPage;
	}
}
