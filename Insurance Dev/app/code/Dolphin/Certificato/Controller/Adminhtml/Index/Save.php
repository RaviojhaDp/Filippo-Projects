<?php
 
/**
 * Grid Admin Cagegory Map Record Save Controller.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Dolphin\Certificato\Controller\Adminhtml\Index;
 use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory; 
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\Grid\Model\GridFactory
     */
    var $gridFactory;
	protected $filesystem;
    protected $fileUploaderFactory;
	protected $_storeManager;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Webkul\Grid\Model\GridFactory $gridFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
         \Dolphin\Certificato\Model\CertificatoFactory $gridFactory,
		 \Magento\Framework\Filesystem $filesystem,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
        parent::__construct($context);
        $this->gridFactory = $gridFactory;
	$this->filesystem = $filesystem;
        $this->_storeManager = $storeManager;
        $this->fileUploaderFactory = $fileUploaderFactory;
	}
 
    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            $this->_redirect('certificato/index/addrow');
            return;
        }
        try {
            $rowData = $this->gridFactory->create();
			$profileImage = $this->getRequest()->getFiles('filetoupload');
			//print_r($profileImage);
			if(!empty($profileImage)){
			/*------*/
			$fileName = ($profileImage && array_key_exists('name', $profileImage)) ? $profileImage['name'] : null;
					
					$mediaDir = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
					$mediapath = $this->mediaBaseDirectory = rtrim($mediaDir, '/');
					$uploader = $this->fileUploaderFactory->create(['fileId' => 'filetoupload']);
					$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
					$uploader->setAllowRenameFiles(true);
					$path = $mediapath . '/certi_upload/';
					$result = $uploader->save($path);
					$fullname = 'certi_upload/'.$_FILES['filetoupload']['name'];					
					$data['filetoupload'] = $fullname;
                }               
					$rowData->setData($data); 
					//$rowData->setData('filetoupload',$result['file']);
            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }
            $rowData->save();
            $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('certificato/index/index');
    }
 
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dolphin_Certificato::save');
    }
}
