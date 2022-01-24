<?php
/**
 * Webkul_Grid Add New Row Form Admin Block.
 * @category    Webkul
 * @package     Webkul_Grid
 * @author      Webkul Software Private Limited
 *
 */
namespace Dolphin\Certificato\Block\Adminhtml\Index\Edit;
 
 
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
        \Dolphin\Certificato\Model\Status $options,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        array $data = []
    ) 
    {
        $this->_objectManager = $objectmanager;
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

            if($model->getData("purchase_date") == "1970-01-01"){
               $model->setpurchaseDate(""); 
            }
            if($model->getData("partner_dob") == "1970-01-01"){
               $model->setPartnerDob(""); 
            }
            if($model->getData("chidren_dob_one") == "1970-01-01"){
               $model->setChidrenDobOne(""); 
            }
            if($model->getData("chidren_dob_two") == "1970-01-01"){
               $model->setChidrenDobTwo(""); 
            }
            if($model->getData("chidren_dob_three") == "1970-01-01"){
               $model->setChidrenDobThree(""); 
            }
            if($model->getData("chidren_dob_four") == "1970-01-01"){
               $model->setChidrenDobFour(""); 
            }
            if($model->getData("chidren_dob_five") == "1970-01-01"){
               $model->setChidrenDobFive(""); 
            }
             if($model->getData("chidren_dob_six") == "1970-01-01"){
               $model->setChidrenDobSix(""); 
            }

            if($model->getData("partner_dob_single") == "1970-01-01"){
               $model->setPartnerDobSingle(""); 
            }

            if($model->getData("first_chidren_dob_one") == "1970-01-01"){
               $model->setFirstChidrenDobOne(""); 
            }
            if($model->getData("first_chidren_dob_two") == "1970-01-01"){
               $model->setFirstChidrenDobTwo(""); 
            }
            if($model->getData("first_chidren_dob_three") == "1970-01-01"){
               $model->setFirstChidrenDobThree(""); 
            }
            if($model->getData("first_chidren_dob_four") == "1970-01-01"){
               $model->setFirstChidrenDobFour(""); 
            }
            if($model->getData("first_chidren_dob_five") == "1970-01-01"){
               $model->setFirstChidrenDobFive(""); 
            }
            if($model->getData("first_chidren_dob_six") == "1970-01-01"){
               $model->setFirstChidrenDobSix(""); 
            }

            if($model->getData("engaged_chidren_dob_one") == "1970-01-01"){
               $model->setEngagedChidrenDobOne(""); 
            }
            if($model->getData("engaged_chidren_dob_two") == "1970-01-01"){
               $model->setEngagedChidrenDobTwo(""); 
            }
            if($model->getData("engaged_chidren_dob_three") == "1970-01-01"){
               $model->setEngagedChidrenDobThree(""); 
            }
            if($model->getData("engaged_chidren_dob_four") == "1970-01-01"){
               $model->setEngagedChidrenDobFour(""); 
            }
            if($model->getData("engaged_chidren_dob_five") == "1970-01-01"){
               $model->setEngagedChidrenDobFive(""); 
            }
            if($model->getData("engaged_chidren_dob_six") == "1970-01-01"){
               $model->setEngagedChidrenDobSix(""); 
            }

        if ($model->getCertificatoId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Row Data'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('certificato_id', 'hidden', ['name' => 'certificato_id']);
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
            ]
        );
        $fieldset->addField(
            'region',
            'text',
            [
                'name' => 'region',
                'label' => __('Region'),
                'id' => 'region',
                'title' => __('Region'),
            ]
			
        );
    
        $fieldset->addField(
            'telephone',
            'text',
            [
                'name' => 'phone',
                'label' => __('Telephone'),
                'id' => 'phone',
                'title' => __('Telephone'),
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
            ]
        );
  
		$fieldset->addField(
        'dob',
        'date',
        [
            'name' => 'dob',
            'label' => __('Date Of Birth'),
            'title' => __('Date Of Birth'),
            'date_format' => 'yyyy-MM-dd',
           
        ]
       );

        $fieldset->addField(
            'sex',
            'select',
            [
                'label' => __('Gender'),
                'title' => __('Gender'),
                'name' => 'sex',
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
            ]
        );
		
		$fieldset->addField(
            'civil_status',
            'select',
            [
                'label' => __('Civil Status'),
                'title' => __('Civil Status'),
                'name' => 'civil_status',
                'options' => ['1' => __('Single'), '2' => __('Married'),'5' => __('Engaged')]
            ]
        );
        
        $fieldset->addField(
            'degree_education',
            'select',
            [
                'label' => __('Degree Education'),
                'title' => __('Degree Education'),
                'name' => 'degree_education',
              
                'options' => ['0' => __('O-levels/Junior High Diploms'), '1' => __('A-levels/Higher High Diploms'),'2' => __('University Pass Degree'),'3' => __('University Honours Degree')]
            ]
        );
		$fieldset->addField(
            'profession',
            'select',
            [
                'label' => __('Profession'),
                'title' => __('Profession'),
                'name' => 'profession',
              
                'options' => ['0' => __('Trader'), '1' => __('Manager'),'2' => __('Employee'),'3' => __('Entrepreneur'),'4' => __('Freelance'),'5' => __('Other')]
            ]
        );
        $fieldset->addField(
            'buying_opportunity',
            'select',
            [
                'label' => __('Purchase Occasion'),
                'title' => __('Purchase Occasion'),
                'name' => 'buying_opportunity',
              
                'options' => ['0' => __('Birthday'), '1' => __('Holiday'),'2' => __('Engagement'),'3' => __('Anniversary'),'4' => __('Special occasion')]
            ]
        );
       
        /*New phase changes 4th_March_2020*/

        $fieldset->addField(
                    'partner_name',
                    'text',
                    [
                        'name' => 'partner_name',
                        'label' => __('Partner Name'),
                        'id' => 'partner_name',
                        'label' => __('Partner Name'),
                        'title' => __('Mobile Phone'),
                    ]
                );

        $fieldset->addField(
                    'partner_surname',
                    'text',
                    [
                        'name' => 'partner_surname',
                        'label' => __('Partner Surname'),
                        'id' => 'partner_surname',
                        'title' => __('Partner Surname'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'partner_dob',
                'date',
                [
                    'name' => 'partner_dob',
                    'label' => __('Partner Birthday'),
                    'title' => __('Partner Birthday'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                'wedding_anniversary',
                'date',
                [
                    'name' => 'wedding_anniversary',
                    'label' => __('Wedding Anniversary'),
                    'title' => __('Wedding Anniversary'),
                  
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               ); 
        $fieldset->addField(
            'no_of_child',
            'select',
            [
                'label' => __('Number of children'),
                'title' => __('Number of children'),
                'name' => 'no_of_child',
                'options' => ['0' => __('0'), '1' => __('1'),'2' => __('2'),'3' => __('3'),'4' => __('4'),'5' => __('5'),'6' => __('6')]
            ]
        );

        $fieldset->addField(
                    'chidren_name_one',
                    'text',
                    [
                        'name' => 'chidren_name_one',
                        'label' => __('Children Name'),
                        'id' => 'chidren_name_one',
                        'label' => __('Children Name'),
                        'title' => __('Children Name'),
                    ]
                );

        $fieldset->addField(
                    'chidren_surname_one',
                    'text',
                    [
                        'name' => 'chidren_surname_one',
                        'label' => __('Children Surname'),
                        'id' => 'chidren_surname_one',
                        'title' => __('Children Surname'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'chidren_dob_one',
                'date',
                [
                    'name' => 'chidren_dob_one',
                    'label' => __('Children Birthday'),
                    'title' => __('Children Birthday'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );
        
        $fieldset->addField(
                    'chidren_name_two',
                    'text',
                    [
                        'name' => 'chidren_name_two',
                        'label' => __('Children Name'),
                        'id' => 'chidren_name_two',
                        'label' => __('Children Name'),
                        'title' => __('Children Name'),
                    ]
                );

        $fieldset->addField(
                    'chidren_surname_two',
                    'text',
                    [
                        'name' => 'chidren_surname_two',
                        'label' => __('Children Surname'),
                        'id' => 'chidren_surname_two',
                        'title' => __('Children Surname'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'chidren_dob_two',
                'date',
                [
                    'name' => 'chidren_dob_two',
                    'label' => __('Children Birthday'),
                    'title' => __('Children Birthday'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'chidren_name_three',
                    'text',
                    [
                        'name' => 'chidren_name_three',
                        'label' => __('Children Name'),
                        'id' => 'chidren_name_three',
                        'label' => __('Children Name'),
                        'title' => __('Children Name'),
                    ]
                );

        $fieldset->addField(
                    'chidren_surname_three',
                    'text',
                    [
                        'name' => 'chidren_surname_three',
                        'label' => __('Children Surname'),
                        'id' => 'chidren_surname_three',
                        'title' => __('Children Surname'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'chidren_dob_three',
                'date',
                [
                    'name' => 'chidren_dob_three',
                    'label' => __('Children Birthday'),
                    'title' => __('Children Birthday'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'chidren_name_four',
                    'text',
                    [
                        'name' => 'chidren_name_four',
                        'label' => __('Children Name'),
                        'id' => 'chidren_name_four',
                        'label' => __('Children Name'),
                        'title' => __('Children Name'),
                    ]
                );

        $fieldset->addField(
                    'chidren_surname_four',
                    'text',
                    [
                        'name' => 'chidren_surname_four',
                        'label' => __('Children Surname'),
                        'id' => 'chidren_surname_four',
                        'title' => __('Children Surname'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'chidren_dob_four',
                'date',
                [
                    'name' => 'chidren_dob_four',
                    'label' => __('Children Birthday'),
                    'title' => __('Children Birthday'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'chidren_name_five',
                    'text',
                    [
                        'name' => 'chidren_name_five',
                        'label' => __('Children Name'),
                        'id' => 'chidren_name_five',
                        'label' => __('Children Name'),
                        'title' => __('Children Name'),
                    ]
                );

        $fieldset->addField(
                    'chidren_surname_five',
                    'text',
                    [
                        'name' => 'chidren_surname_five',
                        'label' => __('Children Surname'),
                        'id' => 'chidren_surname_five',
                        'title' => __('Children Surname'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'chidren_dob_five',
                'date',
                [
                    'name' => 'chidren_dob_five',
                    'label' => __('Children Birthday'),
                    'title' => __('Children Birthday'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'chidren_name_six',
                    'text',
                    [
                        'name' => 'chidren_name_six',
                        'label' => __('Children Name'),
                        'id' => 'chidren_name_six',
                        'label' => __('Children Name'),
                        'title' => __('Children Name'),
                    ]
                );

        $fieldset->addField(
                    'chidren_surname_six',
                    'text',
                    [
                        'name' => 'chidren_surname_six',
                        'label' => __('Children Surname'),
                        'id' => 'chidren_surname_six',
                        'title' => __('Children Surname'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'chidren_dob_six',
                'date',
                [
                    'name' => 'chidren_dob_six',
                    'label' => __('Children Birthday'),
                    'title' => __('Children Birthday'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );


        /*------------------------Engaged--------------------*/
        $fieldset->addField(
            'first_no_of_child',
            'select',
            [
                'label' => __('Number of children Single'),
                'title' => __('Number of children Single'),
                'name' => 'first_no_of_child',
                'options' => ['0' => __('0'), '1' => __('1'),'2' => __('2'),'3' => __('3'),'4' => __('4'),'5' => __('5'),'6' => __('6')]
            ]
        );

        $fieldset->addField(
                    'first_chidren_name_one',
                    'text',
                    [
                        'name' => 'first_chidren_name_one',
                        'label' => __('Children Name(Single)'),
                        'id' => 'first_chidren_name_one',
                        'label' => __('Children Name(Single)'),
                        'title' => __('Children Name(Single)'),
                    ]
                );

        $fieldset->addField(
                    'first_chidren_surname_one',
                    'text',
                    [
                        'name' => 'first_chidren_surname_one',
                        'label' => __('Children Surname(Single)'),
                        'id' => 'first_chidren_surname_one',
                        'title' => __('Children Surname(Single)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'first_chidren_dob_one',
                'date',
                [
                    'name' => 'first_chidren_dob_one',
                    'label' => __('Children Birthday(Single)'),
                    'title' => __('Children Birthday(Single)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );
        
        $fieldset->addField(
                    'first_chidren_name_two',
                    'text',
                    [
                        'name' => 'first_chidren_name_two',
                        'label' => __('Children Name(Single)'),
                        'id' => 'chidren_name_two',
                        'label' => __('Children Name(Single)'),
                        'title' => __('Children Name(Single)'),
                    ]
                );

        $fieldset->addField(
                    'first_chidren_surname_two',
                    'text',
                    [
                        'name' => 'first_chidren_surname_two',
                        'label' => __('Children Surname(Single)'),
                        'id' => 'first_chidren_surname_two(Single)',
                        'title' => __('Children Surname(Single)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'first_chidren_dob_two',
                'date',
                [
                    'name' => 'first_chidren_dob_two',
                    'label' => __('Children Birthday(Single)'),
                    'title' => __('Children Birthday(Single)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'first_chidren_name_three',
                    'text',
                    [
                        'name' => 'first_chidren_name_three',
                        'label' => __('Children Name(Single)'),
                        'id' => 'first_chidren_name_three',
                        'label' => __('Children Name(Single)'),
                        'title' => __('Children Name(Single)'),
                    ]
                );

        $fieldset->addField(
                    'first_chidren_surname_three',
                    'text',
                    [
                        'name' => 'first_chidren_surname_three',
                        'label' => __('Children Surname(Single)'),
                        'id' => 'first_chidren_surname_three',
                        'title' => __('Children Surname(Single)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'first_chidren_dob_three',
                'date',
                [
                    'name' => 'first_chidren_dob_three',
                    'label' => __('Children Birthday(Single)'),
                    'title' => __('Children Birthday(Single)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'first_chidren_name_four',
                    'text',
                    [
                        'name' => 'first_chidren_name_four',
                        'label' => __('Children Name(Single)'),
                        'id' => 'chidren_name_four',
                        'label' => __('Children Name(Single)'),
                        'title' => __('Children Name(Single)'),
                    ]
                );

        $fieldset->addField(
                    'first_chidren_surname_four',
                    'text',
                    [
                        'name' => 'first_chidren_surname_four',
                        'label' => __('Children Surname(Single)'),
                        'id' => 'chidren_surname_four',
                        'title' => __('Children Surname(Single)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'first_chidren_dob_four',
                'date',
                [
                    'name' => 'first_chidren_dob_four',
                    'label' => __('Children Birthday(Single)'),
                    'title' => __('Children Birthday(Single)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'first_chidren_name_five',
                    'text',
                    [
                        'name' => 'first_chidren_name_five',
                        'label' => __('Children Name(Single)'),
                        'id' => 'first_chidren_name_five',
                        'label' => __('Children Name(Single)'),
                        'title' => __('Children Name(Single)'),
                    ]
                );

        $fieldset->addField(
                    'first_chidren_surname_five',
                    'text',
                    [
                        'name' => 'first_chidren_surname_five',
                        'label' => __('Children Surname(Single)'),
                        'id' => 'first_chidren_surname_five',
                        'title' => __('Children Surname(Single)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'first_chidren_dob_five',
                'date',
                [
                    'name' => 'first_chidren_dob_five',
                    'label' => __('Children Birthday(Single)'),
                    'title' => __('Children Birthday(Single)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'first_chidren_name_six',
                    'text',
                    [
                        'name' => 'first_chidren_name_six',
                        'label' => __('Children Name(Single)'),
                        'id' => 'first_chidren_name_six',
                        'label' => __('Children Name(Single)'),
                        'title' => __('Children Name(Single)'),
                    ]
                );

        $fieldset->addField(
                    'first_chidren_surname_six',
                    'text',
                    [
                        'name' => 'first_chidren_surname_six',
                        'label' => __('Children Surname(Single)'),
                        'id' => 'first_chidren_surname_six',
                        'title' => __('Children Surname(Single)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'first_chidren_dob_six',
                'date',
                [
                    'name' => 'first_chidren_dob_six',
                    'label' => __('Children Birthday(Single)'),
                    'title' => __('Children Birthday(Single)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );
        /*Engaged*/

         $fieldset->addField(
            'engaged_no_of_child',
            'select',
            [
                'label' => __('Number of children Single'),
                'title' => __('Number of children Single'),
                'name' => 'engaged_no_of_child',
                'options' => ['0' => __('0'), '1' => __('1'),'2' => __('2'),'3' => __('3'),'4' => __('4'),'5' => __('5'),'6' => __('6')]
            ]
        );

        $fieldset->addField(
                    'engaged_chidren_name_one',
                    'text',
                    [
                        'name' => 'engaged_chidren_name_one',
                        'label' => __('Children Name(Engaged)'),
                        'id' => 'engaged_chidren_name_one',
                        'label' => __('Children Name(Engaged)'),
                        'title' => __('Children Name(Engaged)'),
                    ]
                );

        $fieldset->addField(
                    'engaged_chidren_surname_one',
                    'text',
                    [
                        'name' => 'engaged_chidren_surname_one',
                        'label' => __('Children Surname(Engaged)'),
                        'id' => 'engaged_chidren_surname_one',
                        'title' => __('Children Surname(Engaged)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'engaged_chidren_dob_one',
                'date',
                [
                    'name' => 'engaged_chidren_dob_one',
                    'label' => __('Children Birthday(Engaged)'),
                    'title' => __('Children Birthday(Engaged)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );
        
        $fieldset->addField(
                    'engaged_chidren_name_two',
                    'text',
                    [
                        'name' => 'engaged_chidren_name_two',
                        'label' => __('Children Name(Engaged)'),
                        'id' => 'chidren_name_two',
                        'label' => __('Children Name(Engaged)'),
                        'title' => __('Children Name(Engaged)'),
                    ]
                );

        $fieldset->addField(
                    'engaged_chidren_surname_two',
                    'text',
                    [
                        'name' => 'engaged_chidren_surname_two',
                        'label' => __('Children Surname(Engaged)'),
                        'id' => 'engaged_chidren_surname_two(Engaged)',
                        'title' => __('Children Surname(Engaged)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'engaged_chidren_dob_two',
                'date',
                [
                    'name' => 'engaged_chidren_dob_two',
                    'label' => __('Children Birthday(Engaged)'),
                    'title' => __('Children Birthday(Engaged)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'engaged_chidren_name_three',
                    'text',
                    [
                        'name' => 'engaged_chidren_name_three',
                        'label' => __('Children Name(Engaged)'),
                        'id' => 'engaged_chidren_name_three',
                        'label' => __('Children Name(Engaged)'),
                        'title' => __('Children Name(Engaged)'),
                    ]
                );

        $fieldset->addField(
                    'engaged_chidren_surname_three',
                    'text',
                    [
                        'name' => 'engaged_chidren_surname_three',
                        'label' => __('Children Surname(Engaged)'),
                        'id' => 'engaged_chidren_surname_three',
                        'title' => __('Children Surname(Engaged)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'engaged_chidren_dob_three',
                'date',
                [
                    'name' => 'engaged_chidren_dob_three',
                    'label' => __('Children Birthday(Engaged)'),
                    'title' => __('Children Birthday(Engaged)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'engaged_chidren_name_four',
                    'text',
                    [
                        'name' => 'engaged_chidren_name_four',
                        'label' => __('Children Name(Engaged)'),
                        'id' => 'chidren_name_four',
                        'label' => __('Children Name(Engaged)'),
                        'title' => __('Children Name(Engaged)'),
                    ]
                );

        $fieldset->addField(
                    'engaged_chidren_surname_four',
                    'text',
                    [
                        'name' => 'engaged_chidren_surname_four',
                        'label' => __('Children Surname(Engaged)'),
                        'id' => 'chidren_surname_four',
                        'title' => __('Children Surname(Engaged)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'engaged_chidren_dob_four',
                'date',
                [
                    'name' => 'engaged_chidren_dob_four',
                    'label' => __('Children Birthday(Engaged)'),
                    'title' => __('Children Birthday(Engaged)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'engaged_chidren_name_five',
                    'text',
                    [
                        'name' => 'engaged_chidren_name_five',
                        'label' => __('Children Name(Engaged)'),
                        'id' => 'engaged_chidren_name_five',
                        'label' => __('Children Name(Engaged)'),
                        'title' => __('Children Name(Engaged)'),
                    ]
                );

        $fieldset->addField(
                    'engaged_chidren_surname_five',
                    'text',
                    [
                        'name' => 'engaged_chidren_surname_five',
                        'label' => __('Children Surname(Engaged)'),
                        'id' => 'engaged_chidren_surname_five',
                        'title' => __('Children Surname(Engaged)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'engaged_chidren_dob_five',
                'date',
                [
                    'name' => 'engaged_chidren_dob_five',
                    'label' => __('Children Birthday(Engaged)'),
                    'title' => __('Children Birthday(Engaged)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );

        $fieldset->addField(
                    'engaged_chidren_name_six',
                    'text',
                    [
                        'name' => 'engaged_chidren_name_six',
                        'label' => __('Children Name(Engaged)'),
                        'id' => 'engaged_chidren_name_six',
                        'label' => __('Children Name(Engaged)'),
                        'title' => __('Children Name(Engaged)'),
                    ]
                );

        $fieldset->addField(
                    'engaged_chidren_surname_six',
                    'text',
                    [
                        'name' => 'engaged_chidren_surname_six',
                        'label' => __('Children Surname(Engaged)'),
                        'id' => 'engaged_chidren_surname_six',
                        'title' => __('Children Surname(Engaged)'),
                        //'class' => 'required-entry',
                        
                    ]
                );

       
        $fieldset->addField(
                'engaged_chidren_dob_six',
                'date',
                [
                    'name' => 'engaged_chidren_dob_six',
                    'label' => __('Children Birthday(Engaged)'),
                    'title' => __('Children Birthday(Engaged)'),
                    'date_format' => 'yyyy-MM-dd',
                   
                ]
               );
        /*Engaged*/
        

        /*New phase changes*/
        $fieldset->addField(
            'equpiment',
            'text',
            [
                'name' => 'equpiment',
                'label' => __('Equpiment'),
                'id' => 'equpiment',
                'title' => __('Equpiment'),
               // 'class' => 'required-entry',
                
            ]
        );
         $fieldset->addField(
            'model',
            'text',
            [
                'name' => 'model',
                'label' => __('Model'),
                'id' => 'model',
                'title' => __('Model'),
                //'class' => 'required-entry',
                
            ]
        );
        $fieldset->addField(
            'serial_number',
            'text',
            [
                'name' => 'serial_number',
                'label' => __('Serial Number'),
                'id' => 'serial_number',
                'title' => __('Serial Number'),
                //'class' => 'required-entry',
                
            ]
        );
        $fieldset->addField(
            'name_boutique_retailer',
            'text',
            [
                'name' => 'name_boutique_retailer',
                'label' => __('Name Boutique / Retailer'),
                'id' => 'name_boutique_retailer',
                'title' => __('Name Boutique / Retailer'),
               // 'class' => 'required-entry',
                
            ]
        );
		 
        $fieldset->addField(
            'add_boutique_retailer',
            'text',
            [
                'name' => 'add_boutique_retailer',
                'label' => __('Add Boutique / Retailer'),
                'id' => 'add_boutique_retailer',
                'title' => __('Add Boutique / Retailer'),
               // 'class' => 'required-entry',
                
            ]
        );
        $fieldset->addField(
            'seller_name',
            'text',
            [
                'name' => 'seller_name',
                'label' => __('Seller Name'),
                'id' => 'seller_name-2',
                'title' => __('Seller Name'),
               // 'class' => 'required-entry',
                
            ]
        );
         $fieldset->addField(
            'purchase_date',
            'date',
            [
                'name' => 'purchase_date',
                'label' => __('Purchase Date'),
				'date_format' => 'yyyy-MM-dd',
            ]
        );
		$fieldset->addField(
				'receipt_number',
				'text',
				[
					'name' => 'receipt_number',
					'label' => __('Receipt Number'),
					'id' => 'receipt_number',
					'title' => __('Receipt Number'),
					//'class' => 'required-entry',
					
				]
			);
	
		/*$fieldset->addField(
        		'filetoupload',
        		'image',
        		[
        				'title' => __('Receipt Image (jpg, jpeg, gif, png) *'),
        				'label' => __('Receipt Image (jpg, jpeg, gif, png) *'),
        				'name' => 'filetoupload',
        				'note' => 'Allow image type: jpg, jpeg, gif, png',
        		]
        );*/
		
		$fieldset->addField(
            'status',
            'select',
            [
                'name' => 'is_active',
                'label' => __('Status'),
                'id' => 'is_active',
                'title' => __('Status'),
                'values' => $this->_options->getOptionArray(),
                'class' => 'status',
            ]
        );

    $fieldset->addType('csvfile', 'Dolphin\Certificato\Block\Adminhtml\Index\Csvfile');

    $fieldset->addField(
        'file',
        'csvfile',
        [
            'name'  => 'csvfile',
            'label' => __('csvfile'),
            'title' => __('csvfile'),

        ]
    );
     
		if($model){
			
             $customerObj = $this->_objectManager->create('Magento\Customer\Model\Customer')->load($model->getNameBoutiqueRetailer());
             if(!empty($customerObj->getData())){
                $customerAddress = array();
                foreach ($customerObj->getAddresses() as $address) {
                    $customerAddress[] = $address->toArray();
                }
                
                $model->setNameBoutiqueRetailer($customerAddress[0]['firstname']);
                $model->setAddBoutiqueRetailer($customerAddress[0]['street']);
             }

			$form->setValues($model->getData());
		}
        
        $form->setUseContainer(true);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
}
