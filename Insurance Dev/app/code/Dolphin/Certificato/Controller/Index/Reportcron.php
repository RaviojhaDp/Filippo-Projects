<?php

namespace Dolphin\Certificato\Controller\Index;

class Reportcron extends \Magento\Framework\App\Action\Action {

protected $model;

/**
 * @param \Magento\Framework\App\Action\Context $context
 * @param \Demo\HelloWorld\Model\Customer $customer
 */
public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Dolphin\Certificato\Model\Reportcron $model
) {
    $this->model = $model;

    parent::__construct($context);
}

	public function execute() {
		$this->model->reportCron();
	}

}