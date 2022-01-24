<?php
/**
 * Webkul_Grid Add New Row Form Admin Block.
 * @category    Webkul
 * @package     Webkul_Grid
 * @author      Webkul Software Private Limited
 *
 */
namespace Dolphin\Claim\Block\Adminhtml\Index\Edit;
 
 
/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Dolphin\Claim\Model\Status $options,
        array $data = []
    ) 
    {
        $this->_options = $options;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $model = $this->_coreRegistry->registry('row_data');
		/*echo "<pre>";
		print_r($model->getData());
		die;*/
        $form = $this->_formFactory->create(
            ['data' => [
                            'id' => 'edit_form', 
                            'enctype' => 'multipart/form-data', 
                            'action' => $this->getData('action'), 
                            'method' => 'post'
                        ]
            ]
        );
 
        $form->setHtmlIdPrefix('wkgrid_');
		if($model != ""){
        if ($model->getClaimId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Row Data'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('claim_id', 'hidden', ['name' => 'claim_id']);
        } 
        }
		else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add Row Data'), 'class' => 'fieldset-wide']
            );
		}
 
	$fieldset->addField(
            'customer_group_id',
            'select',
            [
                'label' => __('Customer Group'),
                'title' => __('Customer Group'),
                'name' => 'customer_group_id',
                'required' => true,
                'options' => ['3' => __('Retailer'), '4' => __('Boutique'),'5' => __('Client')]
            ]
        ); 
        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'id' => 'name',
                'title' => __('Name'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );
		$fieldset->addField(
            'surname',
            'text',
            [
                'name' => 'surname',
                'label' => __('Surname'),
                'id' => 'surname',
                'title' => __('Surname'),
                //'class' => 'required-entry',
               // 'required' => true,
            ]
        );
        $fieldset->addField(
            'address',
            'text',
            [
                'name' => 'address',
                'label' => __('Address'),
                'id' => 'address',
                'title' => __('Address'),
               // 'class' => 'required-entry',
                //'required' => true,
            ]
        );
        $fieldset->addField(
            'zipcode',
            'text',
            [
                'name' => 'zipcode',
                'label' => __('Zipcode'),
                'id' => 'zipcode',
                'title' => __('Zipcode'),
             //   'class' => 'required-entry',
               // 'required' => true,
            ]
        );
        $fieldset->addField(
            'city',
            'text',
            [
                'name' => 'city',
                'label' => __('City'),
                'id' => 'city',
                'title' => __('City'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );
         $fieldset->addField(
            'left_country',
            'text',
            [
                'name' => 'left_country',
                'label' => __('Country'),
                'id' => 'left_country',
                'title' => __('Country'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );
        $fieldset->addField(
            'province',
            'text',
            [
                'name' => 'province',
                'label' => __('Province'),
                'id' => 'province',
                'title' => __('Province'),
               // 'class' => 'required-entry',
               // 'required' => true,
            ]
			
        );
        /*$fieldset->addField(
            'state',
            'text',
            [
                'name' => 'state',
                'label' => __('State'),
                'id' => 'state',
                'title' => __('State'),
               // 'class' => 'required-entry',
                //'required' => true,
            ]
        );*/
        $fieldset->addField(
            'phone',
            'text',
            [
                'name' => 'phone',
                'label' => __('Telephone'),
                'id' => 'phone',
                'title' => __('Telephone'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );
        $fieldset->addField(
            'mobile_phone',
            'text',
            [
                'name' => 'mobile_phone',
                'label' => __('Mobile Phone'),
                'id' => 'mobile_phone',
                'title' => __('Mobile Phone'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );
        $fieldset->addField(
            'fiscal_code',
            'text',
            [
                'name' => 'fiscal_code',
                'label' => __('Fiscal Code'),
                'id' => 'fiscal_code-2',
                'title' => __('Fiscal Code'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );
  
		$fieldset->addField(
        'dob',
        'date',
        [
            'name' => 'dob',
            'label' => __('Date Of Birth'),
            'title' => __('Date Of Birth'),
            'required' => true,
            'date_format' => 'yyyy-MM-dd',
           
        ]
       );
        //~ $fieldset->addField(
            //~ 'gender',
            //~ 'text',
            //~ [
                //~ 'name' => 'gender',
                //~ 'label' => __('Gender'),
                //~ 'id' => 'gender',
                //~ 'title' => __('Gender'),
                //~ //'class' => 'required-entry',
                //~ //'required' => true,
            //~ ]
        //~ );
        $fieldset->addField(
            'sex',
            'select',
            [
                'label' => __('Gender'),
                'title' => __('Gender'),
                'name' => 'sex',
                'required' => true,
                'options' => ['0' => __(''), 'Male' => __('Male'),'Female' => __('Female'),'Ntd' => __('I prefer not to declare it')]
            ]
        );
		$fieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'id' => 'email',
                'title' => __('Email'),
                //'class' => 'required-entry validate-email',
               // 'required' => true,
            ]
        );
		
		
        $fieldset->addField(
            'equipment',
            'text',
            [
                'name' => 'equipment',
                'label' => __('Equpiment'),
                'id' => 'equipment',
                'title' => __('Equpiment'),
               // 'class' => 'required-entry',
                //'required' => true,
            ]
        );
       /* $fieldset->addField(
            'material-code',
            'text',
            [
                'name' => 'material-code',
                'label' => __('Material code'),
                'id' => 'material-code',
                'title' => __('Serial Num'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );*/
         //~ $fieldset->addField(
            //~ 'model',
            //~ 'text',
            //~ [
                //~ 'name' => 'model',
                //~ 'label' => __('Model'),
                //~ 'id' => 'model',
                //~ 'title' => __('Model'),
                //~ //'class' => 'required-entry',
                //~ //'required' => true,
            //~ ]
        //~ );
        /*$fieldset->addField(
            'serial_number',
            'text',
            [
                'name' => 'serial_number',
                'label' => __('Serial Number'),
                'id' => 'serial_number',
                'title' => __('Serial Number'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );*/
        $fieldset->addField(
            'name_boutique_retailer',
            'text',
            [
                'name' => 'name_boutique_retailer',
                'label' => __('Name Boutique / Retailer'),
                'id' => 'name_boutique_retailer',
                'title' => __('Name Boutique / Retailer'),
               // 'class' => 'required-entry',
                //'required' => true,
            ]
        );
		 
        //~ $fieldset->addField(
            //~ 'add_boutique_retailer',
            //~ 'text',
            //~ [
                //~ 'name' => 'add_boutique_retailer',
                //~ 'label' => __('Add Boutique / Retailer'),
                //~ 'id' => 'add_boutique_retailer',
                //~ 'title' => __('Add Boutique / Retailer'),
               //~ // 'class' => 'required-entry',
                //~ //'required' => true,
            //~ ]
        //~ );
        
         $fieldset->addField(
            'purchase_date',
            'date',
            [
                'name' => 'purchase_date',
                'label' => __('Purchase Date'),
                'required' => true,
				'date_format' => 'yyyy-MM-dd',
            ]
        );
        
        $fieldset->addField(
            'left_date',
            'text',
            [
                'name' => 'left_date',
                'label' => __('Casualty data'),
                'id' => 'left_date',
                'title' => __('Casualty data'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );
        
        $fieldset->addField(
            'left_address',
            'text',
            [
                'name' => 'left_address',
                'label' => __('Casualty address'),
                'id' => 'left_address',
                'title' => __('Casualty address'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );
        
        $fieldset->addField(
            'left_zipcode',
            'text',
            [
                'name' => 'left_zipcode',
                'label' => __('Postal code casualty'),
                'id' => 'left_zipcode',
                'title' => __('Postal code casualty'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );
        $fieldset->addField(
            'left_city',
            'text',
            [
                'name' => 'left_city',
                'label' => __('City casualty'),
                'id' => 'left_city',
                'title' => __('City casualty'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );
        /* $fieldset->addField(
            'incident-status',
            'text',
            [
                'name' => 'incident-status',
                'label' => __('State casualty'),
                'id' => 'incident-status',
                'title' => __('State casualty'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );*/
        $fieldset->addField(
            'date_of_termination',
            'date',
            [
                'name' => 'date_of_termination',
                'label' => __('Date of termination'),
                'required' => true,
				'date_format' => 'yyyy-MM-dd',
            ]
        );
        
        
        /*$fieldset->addField(
            'authority',
            'text',
            [
                'name' => 'authority',
                'label' => __('Authority'),
                'id' => 'authority',
                'title' => __('Authority'),
               // 'class' => 'required-entry',
                //'required' => true,
            ]
        );
*/
        $fieldset->addField(
            'authority',
            'text',
            [
                'name' => 'authority',
                'label' => __('Authority'),
                'id' => 'authority',
                'title' => __('Authority'),
               // 'class' => 'required-entry',
                //'required' => true,
            ]
        );
        
        $fieldset->addField(
            'location_authority',
            'text',
            [
                'name' => 'location_authority',
                'label' => __('location of the Authority'),
                'id' => 'location_authority',
                'title' => __('location of the Authority'),
               // 'class' => 'required-entry',
                //'required' => true,
            ]
        );
        
         $fieldset->addField(
            'doc_number',
            'text',
            [
                'name' => 'doc_number',
                'label' => __('Document Number issued'),
                'id' => 'doc_number',
                'title' => __('Document Number issued'),
               // 'class' => 'required-entry',
                //'required' => true,
            ]
        );
        
         $fieldset->addField(
            'describe_events',
            'text',
            [
                'name' => 'describe_events',
                'label' => __('Describing events'),
                'id' => 'describe_events',
                'title' => __('Describing events'),
               // 'class' => 'required-entry',
                //'required' => true,
            ]
        );
	
	$fieldset->addField(
            'complaint',
            'text',
            [
                'name' => 'complaint',
                'label' => __('Written complaint'),
                'id' => 'complaint',
                'title' => __('Written complaint'),
               // 'class' => 'required-entry',
                //'required' => true,
            ]
        );
        
        
        
        $fieldset->addField(
            'daimiani_spa',
            'text',
            [
                'name' => 'daimiani_spa',
                'label' => __('Delivery receipt of Damiani SpA'),
                'id' => 'daimiani_spa',
                'title' => __('Delivery receipt of Damiani SpA'),
               // 'class' => 'required-entry',
                //'required' => true,
            ]
        );
        
        $fieldset->addField(
        		'authenticity',
        		'file',
        		[
        				'title' => __('Authenticity'),
        				'label' => __('Authenticity'),
        				'name' => 'authenticity',
        				'note' => 'Allow image type: jpg, jpeg, gif, png',
        		]
        );
	
		if($model){
			$form->setValues($model->getData());
		}
        
        $form->setUseContainer(true);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
}
