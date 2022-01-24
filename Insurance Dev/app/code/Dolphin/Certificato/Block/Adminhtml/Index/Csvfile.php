<?php
/**
 * Webkul_Grid Add New Row Form Admin Block.
 * @category    Webkul
 * @package     Webkul_Grid
 * @author      Webkul Software Private Limited
 *
 */

namespace Dolphin\Certificato\Block\Adminhtml\Index;

use Magento\Framework\DataObject;

class Csvfile extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    protected $_assetRepo;


    public function __construct(  \Magento\Framework\Registry $registry,
         \Magento\Framework\View\Asset\Repository $assetRepo
    ) {
         $this->_assetRepo = $assetRepo;
         $this->_coreRegistry = $registry;
    }

    public function getElementHtml()
    {
        if ($this->_coreRegistry->registry('row_data') != ''){
          $certificap_data = $this->_coreRegistry->registry('row_data')->getData();
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $base_url_config = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue("web/unsecure/base_url");
          //$csvFile = $this->_assetRepo->getUrl('Vendor_Module::csv/yourfile.csv');
          $csvFile = $base_url_config."it/insurancepdf/document/show?certificato_code=".$certificap_data['certificato_code'];
          $csvLink = "<a href=".$csvFile." target='_blank'>Download Sign Document</a>";
          return $csvLink;
        }
        
    }

}