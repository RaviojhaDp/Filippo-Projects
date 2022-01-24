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

namespace Dolphin\Iclients\Api\Data;

interface IclientsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const STORE_ID = 'store_id';
    const URL_PARAM = 'url_param';
    const HEADER = 'header';
    const ICLIENTS_ID = 'iclients_id';
    const WEBSITE_LINK = 'website_link';
    const NAME = 'name';
    const IDENTIFY_CODE = 'identify_code';
    const LOGO = 'logo';
    const DISPLAY_LOGO = 'display_logo';

    /**
     * Get iclients_id
     * @return string|null
     */
    public function getIclientsId();

    /**
     * Set iclients_id
     * @param string $iclientsId
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setIclientsId($iclientsId);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setName($name);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Dolphin\Iclients\Api\Data\IclientsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Dolphin\Iclients\Api\Data\IclientsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Dolphin\Iclients\Api\Data\IclientsExtensionInterface $extensionAttributes
    );

    /**
     * Get url_param
     * @return string|null
     */
    public function getUrlParam();

    /**
     * Set url_param
     * @param string $urlParam
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setUrlParam($urlParam);

    /**
     * Get website_link
     * @return string|null
     */
    public function getWebsiteLink();

    /**
     * Set website_link
     * @param string $websiteLink
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setWebsiteLink($websiteLink);

    /**
     * Get identify_code
     * @return string|null
     */
    public function getIdentifyCode();

    /**
     * Set identify_code
     * @param string $identifyCode
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setIdentifyCode($identifyCode);

    /**
     * Get display_logo
     * @return string|null
     */
    public function getDisplayLogo();

    /**
     * Set display_logo
     * @param string $displayLogo
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setDisplayLogo($displayLogo);

    /**
     * Get header
     * @return string|null
     */
    public function getHeader();

    /**
     * Set header
     * @param string $header
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setHeader($header);

    /**
     * Get logo
     * @return string|null
     */
    public function getLogo();

    /**
     * Set logo
     * @param string $logo
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setLogo($logo);

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId();

    /**
     * Set store_id
     * @param string $storeId
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     */
    public function setStoreId($storeId);
}
