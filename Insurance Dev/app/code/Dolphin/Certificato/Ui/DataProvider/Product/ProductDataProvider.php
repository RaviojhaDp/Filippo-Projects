<?php namespace Dolphin\Certificato\Ui\DataProvider\Product;

use Dolphin\Certificato\Model\CertificatoFactory;

class ProductDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $aclRetriever;
    protected $authSession;

    public function __construct(
        CertificatoFactory $collectionFactory,
         \Magento\Authorization\Model\Acl\AclRetriever $aclRetriever,
        \Magento\Backend\Model\Auth\Session $authSession, 
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->aclRetriever = $aclRetriever;
        $this->authSession = $authSession;
        $collection = $collectionFactory->create();
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );

       $user = $this->authSession->getUser();
        $role = $user->getRole();
        $resources = $this->aclRetriever->getAllowedResourcesByRole($role->getId());
        $this->collection = $collectionFactory->create()->getCollection();
        $this->collection->addFieldToFilter(['status', 'status'],
                                              [
                                                  ['eq' => '1'],
                                                  ['eq' => '4'] //Deactivate
                                              ]);
        $filter_brand = '';
        if (in_array("Dolphin_Claim::indexd", $resources))
          {
          $filter_brand = "damiani";
          $this->collection->addFieldToFilter('brand', ['eq' => $filter_brand ]);
          }
        elseif (in_array("Dolphin_Claim::indexs", $resources))
          {
          $filter_brand = "salvini";
          $this->collection->addFieldToFilter('brand', ['eq' => $filter_brand ]);
          }
        elseif (in_array("Dolphin_Claim::indexb", $resources))
          {
          $filter_brand = "bliss";
          $this->collection->addFieldToFilter('brand', ['eq' => $filter_brand ]);
          }
        elseif (in_array("Dolphin_Claim::indexr", $resources))
          {
          $filter_brand = "rocca";
          $this->collection->addFieldToFilter('brand', ['eq' => $filter_brand ]);
          }
          elseif (in_array("Dolphin_Claim::indexc", $resources))
          {
          $filter_brand = "calderoni";
          $this->collection->addFieldToFilter('brand', ['eq' => $filter_brand ]);
          }
        else{
            return true;
        }

       // $this->collection = $collectionFactory->create()->getCollection()->addFieldToFilter('brand', ['eq' => $filter_brand ]);

        return $this->collection;
                         
    }

}