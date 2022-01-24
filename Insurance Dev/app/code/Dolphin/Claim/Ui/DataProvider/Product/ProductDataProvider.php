<?php namespace Dolphin\Claim\Ui\DataProvider\Product;

use Dolphin\Claim\Model\ClaimFactory;

class ProductDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $aclRetriever;
    protected $authSession;

    public function __construct(
        ClaimFactory $collectionFactory,
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
        $this->collection->addFieldToFilter('status_claim', ['eq' => '1' ]);
        /*$filter_brand = '';
        if (in_array("Dolphin_Claim::indexcd", $resources))
          {
          $filter_brand = "damiani";
          $this->collection->addFieldToFilter('brand', ['eq' => $filter_brand ]);
          }
        elseif (in_array("Dolphin_Claim::indexcs", $resources))
          {
          $filter_brand = "salvini";
          $this->collection->addFieldToFilter('brand', ['eq' => $filter_brand ]);
          }
        elseif (in_array("Dolphin_Claim::indexcb", $resources))
          {
          $filter_brand = "bliss";
          $this->collection->addFieldToFilter('brand', ['eq' => $filter_brand ]);
          }
        elseif (in_array("Dolphin_Claim::indexcr", $resources))
          {
          $filter_brand = "rocca";
          $this->collection->addFieldToFilter('brand', ['eq' => $filter_brand ]);
          }
        else{
            return true;
        }*/

       // $this->collection = $collectionFactory->create()->getCollection()->addFieldToFilter('brand', ['eq' => $filter_brand ]);

        return $this->collection;
                         
    }

}