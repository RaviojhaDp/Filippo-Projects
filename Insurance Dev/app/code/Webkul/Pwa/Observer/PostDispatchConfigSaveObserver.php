<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Pwa
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\Pwa\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Webkul\Pwa\Helper\Data as HelperData;
use Magento\Framework\Filesystem\Io\File as IoFile;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Webkul Pwa PostDispatchConfigSaveObserver Observer.
 */
class PostDispatchConfigSaveObserver implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    private $_messageManager;

    /**
     * @var HelperData
     */
    private $_helper;

    /**
     * @var IoFile
     */
    protected $_filesystemFile;
    protected $_http;
    protected $storeManager;
    protected $file;

    /**
     * @param ManagerInterface $messageManager
     * @param Filesystem       $filesystem
     * @param HelperData       $helper
     */
    public function __construct(
        ManagerInterface $messageManager,
        Filesystem $filesystem,
        HelperData $helper,
        IoFile $filesystemFile,
        File $file,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Request\Http $http
    ) {
        $this->_messageManager = $messageManager;
        $this->_baseDirectory = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $this->_helper = $helper;
        $this->_filesystemFile = $filesystemFile;
        $this->file = $file;
        $this->_http = $http;
        $this->storeManager = $storeManager;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        
        try {
            /**
 * @var \Magento\Framework\ObjectManagerInterface $objManager
*/
            $objManager = \Magento\Framework\App\ObjectManager::getInstance();
            /**
 * @var \Magento\Framework\Module\Dir\Reader $reader
*/
            $reader = $objManager->get(\Magento\Framework\Module\Dir\Reader::class);

            /**
 * @var \Magento\Framework\Filesystem $filesystem
*/
            $filesystem = $objManager->get(\Magento\Framework\Filesystem::class);

            $serviceWorkerJsFile = $reader->getModuleDir(
                '',
                'Webkul_Pwa'
            ).'/view/base/web/js/firebase-messaging-sw.js';

            $serviceWorkerJsDestination = $this->_baseDirectory->getAbsolutePath().'firebase-messaging-sw.js';
            
            $this->_filesystemFile->cp($serviceWorkerJsFile, $serviceWorkerJsDestination);

            $observerRequestData = $observer['request'];
            
            $params = $observerRequestData->getParams();
            if ($params['section'] == 'pwa') {
                $img = explode(".", $this->_helper->getApplicationIcon());
                $img_type = end($img);
                $getFiles = $this->_http->getFiles();
                $file_type = $getFiles->groups['general_settings']['fields']['application_icon']['value']['type'];
                $type =  !empty($file_type)?'"type":'.'"'.$file_type.'"': '"type":'.'"image/'.$img_type.'"';
                $helper_sender = $this->_helper->getSenderId();
                $senderId = !empty($helper_sender)?$helper_sender:$paramsData['application_sender_id']['value'];
                $baseUrl = $this->storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_WEB,
                    true
                );
                $paramsData = $params['groups']['general_settings']['fields'];
                if ($paramsData['status']['value']) {
                    $baseDirPath = $this->_baseDirectory->getAbsolutePath();
                    $manifestFile = $this->file->fileOpen($baseDirPath."/manifest.json", "w");
                    $manifestFileData ='{"name": "'.$paramsData['application_name']['value'].'",
"short_name": "'.$paramsData['application_short_name']['value'].'",
"start_url": "'.$baseUrl.'",
"display": "standalone",
"gcm_sender_id": "103953800507",
"gcm_user_visible_only":"true",
"orientation": "portrait",
"background_color": "'.$paramsData['splash_bg_color']['value'].'",
"theme_color": "'.$paramsData['theme_color']['value'].'",
"icons": [{
    "src": "'.$this->_helper->getMediaUrl('pwa/icon/').$this->_helper->getApplicationIcon().'",
    "sizes": "192x192",
    "type":"image/png"
    },
    {
        "src": "'.$this->_helper->getMediaUrl('pwa/icon/').$this->_helper->getApplicationIcon().'",
        "sizes": "512x512",
        "type":"image/png"
        }]
    
}';
                    $this->file->fileWrite($manifestFile, $manifestFileData);
                    $this->file->fileClose($manifestFile);
                }

                $baseDirPath = $this->_baseDirectory->getAbsolutePath();
                $fcmFile = $this->file->fileOpen($baseDirPath."/fcminit.js", "w");
                $fcmFileData = "var firebaseConfig = {
                    apiKey: '".$this->_helper->getFCMConfigEncrypted('application_apiKey')."',
                    authDomain: '". $this->_helper->getFCMConfig('application_authDomain')."',
                    databaseURL: '". $this->_helper->getFCMConfig('application_databaseURL')."',
                    projectId: '". $this->_helper->getFCMConfig('application_projectId')."',
                    storageBucket: '". $this->_helper->getFCMConfig('application_storageBucket')."',
                    messagingSenderId: '". $this->_helper->getFCMConfigEncrypted('application_sender_id')."',
                    appId: '". $this->_helper->getFCMConfigEncrypted('application_appId')."'
                };
            
                // Initialize Firebase
                firebase.initializeApp(firebaseConfig);";
                $this->file->fileWrite($fcmFile, $fcmFileData);
                $this->file->fileClose($fcmFile);
            }
        } catch (\Exception $e) {
            $this->_messageManager->addError($e->getMessage());
        }
    }
}
