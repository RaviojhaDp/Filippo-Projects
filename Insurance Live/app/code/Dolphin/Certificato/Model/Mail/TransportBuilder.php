<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_EmailDemo
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Dolphin\Certificato\Model\Mail;

class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{
    public function addAttachment($file, $name)
    {
        if (!empty($file) && file_exists($file)) {
            $this->message
            ->createAttachment(
                file_get_contents($file),
                \Zend_Mime::TYPE_OCTETSTREAM,
                \Zend_Mime::DISPOSITION_ATTACHMENT,
                \Zend_Mime::ENCODING_BASE64,
                basename($name)
            );
        }
        return $this;
    }
}