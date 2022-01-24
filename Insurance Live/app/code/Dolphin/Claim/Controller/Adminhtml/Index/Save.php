<?php
 
/**
 * Grid Admin Cagegory Map Record Save Controller.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Dolphin\Claim\Controller\Adminhtml\Index;
 
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\Grid\Model\GridFactory
     */
    var $gridFactory;
	protected $_mediaDirectory;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Webkul\Grid\Model\GridFactory $gridFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
         \Dolphin\Claim\Model\ClaimFactory $gridFactory,
		 \Magento\Framework\Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->gridFactory = $gridFactory;
		$this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
	}
 
    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            $this->_redirect('claim/index/addrow');
            return;
        }
        try {
            $rowData = $this->gridFactory->create();
			$profileImage = $this->getRequest()->getFiles('daimiani_spa');
			//print_r($profileImage);
			
			/*------*/
			$fileName = ($profileImage && array_key_exists('name', $profileImage)) ? $profileImage['name'] : null;
					/** @var \Magento\Framework\ObjectManagerInterface $uploader */
					$uploader = $this->_objectManager->create(
						'Magento\MediaStorage\Model\File\Uploader',
						['fileId' => 'daimiani_spa']
					); 
					$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
					$imageAdapterFactory = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')
						->create();
					$uploader->setAllowRenameFiles(true);
					//$uploader->setFilesDispersion(true);
					$target = $this->_mediaDirectory->getAbsolutePath('daimiani_spa/');     
					$result = $uploader->save($target);
					//$rowData->setFiletoupload($result['file']); 
					//$rowData->setData($data); 
					$rowData->setData('daimiani_spa',$result['file']); 
					
					$profileImage = $this->getRequest()->getFiles('daimiani_spa');
			//print_r($profileImage);
			
			/*------*/
			$profileImage2 = $this->getRequest()->getFiles('complaint');
			$fileName2 = ($profileImage2 && array_key_exists('name', $profileImage2)) ? $profileImage2['name'] : null;
					/** @var \Magento\Framework\ObjectManagerInterface $uploader */
					$uploader2 = $this->_objectManager->create(
						'Magento\MediaStorage\Model\File\Uploader',
						['fileId' => 'complaint']
					); 
					$uploader2->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
					$imageAdapterFactory = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')
						->create();
					$uploader2->setAllowRenameFiles(true);
					//$uploader->setFilesDispersion(true);
					$target2 = $this->_mediaDirectory->getAbsolutePath('complaint/');     
					$result2 = $uploader2->save($target2);
					//$rowData->setFiletoupload($result['file']); 
					$rowData->setData('complaint',$result2['file']); 
					$rowData->setData($data); 
					
            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }
            $rowData->save();
            $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('claim/index/index');
    }
 
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dolphin_Claim::save');
    }
}
