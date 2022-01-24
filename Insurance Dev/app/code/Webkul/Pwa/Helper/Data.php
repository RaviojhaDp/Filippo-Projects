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

namespace Webkul\Pwa\Helper;

/**
 * Pwa data helper.
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_encryptor;

    /**
     * @param Magento\Framework\App\Helper\Context      $context
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor
    ) {
        $this->_storeManager = $storeManager;
        $this->_encryptor = $encryptor;
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/status',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getApplicationName()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/application_name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getApplicationShortName()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/application_short_name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getApplicationIcon()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/application_icon',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSplashBgColor()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/splash_bg_color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getThemeColor()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/theme_color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSenderId()
    {
        return $this->_encryptor->decrypt($this->scopeConfig->getValue(
            'pwa/general_settings/application_sender_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ));
    }

    /**
     * @return string
     */
    public function getServerKey()
    {
        return $this->_encryptor->decrypt($this->scopeConfig->getValue(
            'pwa/general_settings/application_server_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ));
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->_encryptor->decrypt($this->scopeConfig->getValue(
            'pwa/general_settings/application_public_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ));
    }

    /**
     * getMediaUrl get media url
     *
     * @param  string $path
     * @return
     */
    public function getMediaUrl($path = null)
    {
        if ($path) {
            return $this ->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA,
                ['_secure' => true]
            ).$path;
        } else {
            return $this ->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA,
                ['_secure' => true]
            );
        }
    }

    /**
     * @return string
     */
    public function getSecureUrl()
    {
        return $this->scopeConfig->getValue(
            'web/secure/base_url',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->scopeConfig->getValue(
            'pwa/pwa_button_settings/button_position',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

     /**
      * @return string
      */
    public function getTextColor()
    {
        return $this->scopeConfig->getValue(
            'pwa/pwa_button_settings/color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

     /**
      * @return string
      */
    public function getButtonColor()
    {
        return $this->scopeConfig->getValue(
            'pwa/pwa_button_settings/button_color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getFCMConfig($value)
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/'.$value,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getFCMConfigEncrypted($value)
    {
        return $this->_encryptor->decrypt($this->scopeConfig->getValue(
            'pwa/general_settings/'.$value,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ));
    }
}
