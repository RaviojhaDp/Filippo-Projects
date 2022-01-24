<?php
/**
 * Grid Admin Cagegory Map Record Save Controller.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Dolphin\Certificato\Controller\Index;

class Searchcertidash extends \Magento\Framework\App\Action\Action {

    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\SessionFactory $sessionFactory, \Magento\Framework\ObjectManagerInterface $objectmanager, \Dolphin\Certificato\Model\CertificatoFactory $certificatoModelFactory
    ) {
        parent::__construct($context);
        $this->_objectManager = $objectmanager;
        $this->sessionFactory = $sessionFactory;
        $this->certificatoModelFactory = $certificatoModelFactory;
    }

    public function execute() {
        $nameget = $this->getRequest()->getPostValue('data');
        $name = trim(str_replace('_', ' ', $nameget));
        //$cat_name = $this->getRequest()->getPostValue('cat_name');
        //$customer_group_id = $this->getRequest()->getPostValue('customer_group_id');
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $base_url_config = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue("web/unsecure/base_url");

        
        if ( $this->certificatoModelFactory->create()->getCollection()) {
            $sessionCustomerId = $this->sessionFactory->create()->getCustomer()->getId();
           $sessionGroupId = $this->sessionFactory->create()->getCustomer()->getGroupId();
           if($sessionGroupId == '5'){
            $col =  $this->certificatoModelFactory->create()->getCollection()
                ->addFieldToFilter(array('name','surname', 'certificato_code'),
                                    array(
                                        array('like'=>'%'.$name.'%'), 
                                        array('like'=>'%'.$name.'%'),
                                        array('like'=>'%'.$name.'%')
                                    ))

                ->addFieldToFilter('customer_group_id', $sessionGroupId)
                ->addFieldToFilter('customer_id', $sessionCustomerId)
                ->addFieldToFilter('status', '1')
                ->setOrder('certificato_id','DESC');
              
              }else{

              $col = $this->certificatoModelFactory->create()->getCollection()
              ->addFieldToFilter(array('name','surname','certificato_code'),
                                array(
                                    array('like'=>'%'.$name.'%'), 
                                    array('like'=>'%'.$name.'%'),
                                     array('like'=>'%'.$name.'%')
                                ))
                ->addFieldToFilter('name_boutique_retailer', $sessionCustomerId)
                ->addFieldToFilter('status', '1')
                ->setOrder('certificato_id','DESC');
                }
                //echo $col->getSelect();
                //die;
                $html = '';
                foreach ($col->getData() as $key ) { //echo $key['certificato_id'];
                $urlp =  $base_url_config."it/faber/document/show?certificato_code=".$key['certificato_code']."&signid=".$key['doc_signedid'];  if ($key['status'] == '1') {
                              $status =  __('Activated');
                            } else {
                              $status =  __('Deactivated');
                            } 
                    $html .= '<tr><td>'.$key['name'] ." ".$key['surname'] .'</td><td>'.$key['certificato_code'] .'</td><td>'.$key['brand'] .'</td><td>'.$status.'</td><td>'. date("d/m/Y",strtotime($key['created_at'])) .'</td><td>'.date("d/m/Y",strtotime($key['expire_date']))  .'</td><td>
                                <a href="'.$urlp.'">Download</a>'.'</td></tr>';
                }
             echo $html; exit;    
       }

    }

}
