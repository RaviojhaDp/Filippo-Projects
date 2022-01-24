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

namespace Dolphin\Iclients\Model\Data;

use Dolphin\Iclients\Api\Data\IclientsInterface;

class Iclients extends \Magento\Framework\Api\AbstractExtensibleObject implements IclientsInterface
{

    /**
     * Get iclients_id
     * @return string|null
     */
    public function getIclientsId()
    {
        return $this->_get(self::ICLIENTS_ID);
    }

    /**
     * Set iclients_id
     * @param string $iclientsId
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setIclientsId($iclientsId)
    {
        return $this->setData(self::ICLIENTS_ID, $iclientsId);
    }

    /**
     * Get name
     * @return string|null
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Dolphin\Iclients\Api\Data\IclientsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Dolphin\Iclients\Api\Data\IclientsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Dolphin\Iclients\Api\Data\IclientsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get url_param
     * @return string|null
     */
    public function getUrlParam()
    {
        return $this->_get(self::URL_PARAM);
    }

    /**
     * Set url_param
     * @param string $urlParam
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setUrlParam($urlParam)
    {
        return $this->setData(self::URL_PARAM, $urlParam);
    }

    /**
     * Get website_link
     * @return string|null
     */
    public function getWebsiteLink()
    {
        return $this->_get(self::WEBSITE_LINK);
    }

    /**
     * Set website_link
     * @param string $websiteLink
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setWebsiteLink($websiteLink)
    {
        return $this->setData(self::WEBSITE_LINK, $websiteLink);
    }

    /**
     * Get identify_code
     * @return string|null
     */
    public function getIdentifyCode()
    {
        return $this->_get(self::IDENTIFY_CODE);
    }

    /**
     * Set identify_code
     * @param string $identifyCode
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setIdentifyCode($identifyCode)
    {
        return $this->setData(self::IDENTIFY_CODE, $identifyCode);
    }

    /**
     * Get display_logo
     * @return string|null
     */
    public function getDisplayLogo()
    {
        return $this->_get(self::DISPLAY_LOGO);
    }

    /**
     * Set display_logo
     * @param string $displayLogo
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setDisplayLogo($displayLogo)
    {
        return $this->setData(self::DISPLAY_LOGO, $displayLogo);
    }

    /**
     * Get header
     * @return string|null
     */
    public function getHeader()
    {
        return $this->_get(self::HEADER);
    }

    /**
     * Set header
     * @param string $header
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setHeader($header)
    {
        return $this->setData(self::HEADER, $header);
    }

    /**
     * Get logo
     * @return string|null
     */
    public function getLogo()
    {
        return $this->_get(self::LOGO);
    }

    /**
     * Set logo
     * @param string $logo
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setLogo($logo)
    {
        return $this->setData(self::LOGO, $logo);
    }

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId()
    {
        return $this->_get(self::STORE_ID);
    }

    /**
     * Set store_id
     * @param string $storeId
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }
}
