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

class Certistatusclient extends \Magento\Framework\App\Action\Action {

    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\ObjectManagerInterface $objectmanager, \Dolphin\Certificato\Model\CertificatoFactory $certificatoModelFactory
    ) {
        parent::__construct($context);
        $this->_objectManager = $objectmanager;
        $this->certificatoModelFactory = $certificatoModelFactory;
    }

    public function execute() {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $base_url_config = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue("web/unsecure/base_url");
        $data = $this->getRequest()->getPostValue('data');
        $storename = $this->getRequest()->getPostValue('storename');

        $cat_name = $this->getRequest()->getPostValue('cat_name');
        $customer_group_id = $this->getRequest()->getPostValue('customer_group_id');
        $model = $this->certificatoModelFactory->create()->getCollection();
        $model->addFieldToFilter('certificato_id', array('eq' => $data));
        $model->addFieldToFilter('status', array('eq' => 1));
        if (count($model->getData()) > 0) {
            $model = $model->getData();


            $current = strtotime(date("Y-m-d"));
            $date = strtotime($model[0]['expire_date']);

            $datediff = $date - $current;
            $difference = floor($datediff / (60 * 60 * 24));
            $diff = abs($datediff);
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            //if($difference > 1 && $months <= 6 && $years == 0){
            //}
            ?>
            <div class="respose">
                <?php if (strtolower($storename) == "italiano") {
                    ?>
                    <p><?php echo __('Certificato : ') ?><span><?php echo @$model[0]['certificato_code']; ?></span></p>
                    <?php
                } else {
                    ?>
                    <p><?php echo __('Certificate : ') ?><span><?php echo @$model[0]['certificato_code']; ?></span></p>
                <?php } ?>




                <p><?php
                    if ($difference > 1) {
                        echo __('Activated');
                    } else {
                        echo __('Deactivated');
                    }
                    ?></p>
                <p><?php echo __('Activated Date'); ?></br><span><?php echo date("d-m-Y", strtotime(@$model[0]['created_at']));
                    ?></span></p>
                <p><?php echo __('Expire Date'); ?>
                    </br><span><?php echo date("d-m-Y", strtotime(@$model[0]['expire_date'])); ?></span></p> 
                <?php if (strtolower($cat_name) == 'damiani' && $difference > 1 && $months <= 6 && $years == 0) { ?>
                    <div class="Renewal Certificato"><button id="renewal_new"><a href="<?php echo $base_url_config.strtolower($cat_name); ?>/assicurazione.html?<?php echo "certid=" . $data; ?>&renewal=1"><?php echo __('Renewal Certificate') ?></a></button></div>
                <?php } ?>

                        <?php if (($difference > 1) && ($customer_group_id != '5')) { ?>
                    <div class="Create Product"><button type="submit" id="return"><?php
                            if (strtolower($storename) == "italiano") {
                                echo __('RESO PRODOTTO');
                            } else {
                                echo __('Made Product');
                            }
                            ?></button></div>
            <?php } ?> 

                <div class="confirmation-modal-content" style="display: none">
                    <p id="return-con"><?php echo __('Are you sure? You are going to deactivate the Warranty'); ?></p>
                </div>
                <p id="certificato_code" style="display: none"><?php echo @$model[0]['certificato_code'] ?></p>
                <p id="certificato_id" style="display: none"><?php echo @$model[0]['certificato_id'] ?></p>
                <div class="certi-status-contain"><span><?php echo __('You can cancel the certificate, and make the product In a boutique or at an authorized dealer') ?></span></div>
            </div>

            <?php
        } else {
            ?>
            <div class="respose">
                <p><?php echo __('No search result found.') ?>
                </p>
            </div>
            <?php
        }
    }

}
