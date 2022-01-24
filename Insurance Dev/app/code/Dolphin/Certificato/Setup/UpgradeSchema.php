<?php
namespace Dolphin\Certificato\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$installer = $setup;

		$installer->startSetup();

		if(version_compare($context->getVersion(), '1.2.0', '<')) {
			$installer->getConnection()->addColumn(
				$installer->getTable( 'certificato' ),
				'status',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					'nullable' => false,
					'length' => '100',
					'comment'=> 'status'
				]
			);
			$installer->getConnection()->addColumn(
				$installer->getTable( 'certificato' ),
				'created_at',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
					'comment'=> 'created_at'
				]
			);
			$installer->getConnection()->addColumn(
				$installer->getTable( 'certificato' ),
				'updated_at',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE,
					'comment'=> 'updated_at'
				]
			);
		}
		if(version_compare($context->getVersion(), '1.2.1', '<')) {
		$installer->getConnection()->addColumn(
				$installer->getTable( 'certificato' ),
				'customer_id',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					'nullable' => true,
					'comment'=> 'customer_id'
				]
			);
		}
		if(version_compare($context->getVersion(), '1.2.2', '<')) {
		$installer->getConnection()->addColumn(
				$installer->getTable( 'certificato' ),
				'filetoupload',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => true,
					'comment'=> 'filetoupload'
				]
			);
			$installer->getConnection()->addColumn(
				$installer->getTable( 'certificato' ),
				'email',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => true,
					'comment'=> 'email'
				]
			);
		}
		if(version_compare($context->getVersion(), '1.2.3', '<')) {
			$data = [
            'AG' => 'Agrigento',
            'AL' => 'Alessandria',
            'AN' => 'Ancona',
            'AO' => 'Aosta',
            'AR' => 'Arezzo',
            'AP' => 'Ascoli Piceno',
            'AT' => 'Asti',
            'AV' => 'Avellino',
            'BA' => 'Bari',
            'BT' => 'Barletta-Andria-Trani',
            'BL' => 'Belluno',
            'BN' => 'Benevento',
            'BG' => 'Bergamo',
            'BI' => 'Biella',
            'BO' => 'Bologna',
            'BZ' => 'Bolzano',
            'BS' => 'Brescia',
            'BR' => 'Brindisi',
            'CA' => 'Cagliari',
            'CL' => 'Caltanissetta',
            'CB' => 'Campobasso',
            'CI' => 'Carbonia-Iglesias',
            'CE' => 'Caserta',
            'CT' => 'Catania',
            'CZ' => 'Catanzaro',
            'CH' => 'Chieti',
            'CO' => 'Como',
            'CS' => 'Cosenza',
            'CR' => 'Cremona',
            'KR' => 'Crotone',
            'CN' => 'Cuneo',
            'EN' => 'Enna',
            'FM' => 'Fermo',
            'FE' => 'Ferrara',
            'FI' => 'Firenze',
            'FG' => 'Foggia',
            'FC' => 'Forl�-Cesena',
            'FR' => 'Frosinone',
            'GE' => 'Genova',
            'GO' => 'Gorizia',
            'GR' => 'Grosseto',
            'IM' => 'Imperia',
            'IS' => 'Isernia',
            'SP' => 'La Spezia',
            'AQ' => 'L\'Aquila',
            'LT' => 'Latina',
            'LE' => 'Lecce',
            'LC' => 'Lecco',
            'LI' => 'Livorno',
            'LO' => 'Lodi',
            'LU' => 'Lucca',
            'MC' => 'Macerata',
            'MN' => 'Mantova',
            'MS' => 'Massa-Carrara',
            'MT' => 'Matera',
            'ME' => 'Messina',
            'MI' => 'Milano',
            'MO' => 'Modena',
            'MB' => 'Monza e della Brianza',
            'NA' => 'Napoli',
            'NO' => 'Novara',
            'NU' => 'Nuoro',
            'OT' => 'Olbia-Tempio',
            'OR' => 'Oristano',
            'PD' => 'Padova',
            'PA' => 'Palermo',
            'PR' => 'Parma',
            'PV' => 'Pavia',
            'PG' => 'Perugia',
            'PU' => 'Pesaro e Urbino',
            'PE' => 'Pescara',
            'PC' => 'Piacenza',
            'PI' => 'Pisa',
            'PT' => 'Pistoia',
            'PN' => 'Pordenone',
            'PZ' => 'Potenza',
            'PO' => 'Prato',
            'RG' => 'Ragusa',
            'RA' => 'Ravenna',
            'RC' => 'Reggio Calabria',
            'RE' => 'Reggio Emilia',
            'RI' => 'Rieti',
            'RN' => 'Rimini',
            'RM' => 'Roma',
            'RO' => 'Rovigo',
            'SA' => 'Salerno',
            'VS' => 'Medio Campidano',
            'SS' => 'Sassari',
            'SV' => 'Savona',
            'SI' => 'Siena',
            'SR' => 'Siracusa',
            'SO' => 'Sondrio',
            'TA' => 'Taranto',
            'TE' => 'Teramo',
            'TR' => 'Terni',
            'TO' => 'Torino',
            'OG' => 'Ogliastra',
            'TP' => 'Trapani',
            'TN' => 'Trento',
            'TV' => 'Treviso',
            'TS' => 'Trieste',
            'UD' => 'Udine',
            'VA' => 'Varese',
            'VE' => 'Venezia',
            'VB' => 'Verbano-Cusio-Ossola',
            'VC' => 'Vercelli',
            'VR' => 'Verona',
            'VV' => 'Vibo Valentia',
            'VI' => 'Vicenza',
            'VT' => 'Viterbo',
        ];

        foreach ($data as $code => $name) {

            $binds = ['country_id'   => 'IT', 'code' => $code, 'default_name' => $name];
            $setup->getConnection()->insert($setup->getTable('directory_country_region'), $binds);
            $regionId = $setup->getConnection()->lastInsertId($setup->getTable('directory_country_region'));


            $binds = ['locale'=> 'it_IT', 'region_id' => $regionId, 'name'=> $name];
            $setup->getConnection()->insert($setup->getTable('directory_country_region_name'), $binds);
        }

		}
		if(version_compare($context->getVersion(), '1.2.4', '<')) {
		$installer->getConnection()->addColumn(
				$installer->getTable( 'certificato' ),
				'country',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => true,
					'comment'=> 'country'
				]
			);
			$installer->getConnection()->addColumn(
				$installer->getTable( 'certificato' ),
				'expire_date',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
					'comment'=> 'expire_date'
				]
			);
		}

          if(version_compare($context->getVersion(), '1.2.5', '<')) {
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'certificato_code',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'certificato_code'
                        ]
                  );
                  
            }
            if(version_compare($context->getVersion(), '1.2.6', '<')) {
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'receipt_number',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'receipt_number'
                        ]
                  );
                  
            }
            if(version_compare($context->getVersion(), '1.2.7', '<')) {
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'sign_header_id',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'sign_header_id'
                        ]
                  );
                  
            }
            if(version_compare($context->getVersion(), '1.2.8', '<')) {
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'wedding_anniversary',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'wedding_anniversary'
                        ]
                  );
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'partner_name',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'partner_name'
                        ]
                  );
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'partner_surname',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'partner_surname'
                        ]
                  );
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'partner_dob',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'partner_dob'
                        ]
                  );
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'no_of_child',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'no_of_child'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_name_one',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_name_one'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_surname_one',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_surname_one'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_dob_one',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'chidren_dob_one'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_name_two',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_name_two'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_surname_two',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_surname_two'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_dob_two',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'chidren_dob_two'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_name_three',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_name_three'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_surname_three',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_surname_three'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_dob_three',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'chidren_dob_three'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_name_four',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_name_four'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_surname_four',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_surname_four'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_dob_four',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'chidren_dob_four'
                        ]
                  );
            
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_name_five',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_name_five'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_surname_five',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_surname_five'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_dob_five',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'chidren_dob_five'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_name_six',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_name_six'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_surname_six',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'chidren_surname_six'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'chidren_dob_six',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'chidren_dob_six'
                        ]
                  );
            }
            
            if(version_compare($context->getVersion(), '1.2.9', '<')) {

                $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'partner_name_single',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'partner_name_single'
                        ]
                  );
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'partner_surname_single',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'partner_surname_single'
                        ]
                  );
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'partner_dob_single',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'partner_dob_single'
                        ]
                  );  
            }
             if(version_compare($context->getVersion(), '1.2.10', '<')) {

                  $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'stone_code',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'stone_code'
                        ]
                  );
            }

            if(version_compare($context->getVersion(), '1.2.11', '<')) {
            
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_no_of_child',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'no_of_child'
                        ]
                  );



             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_name_one',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_name_one'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_surname_one',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_surname_one'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_dob_one',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'first_chidren_dob_one'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_name_two',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_name_two'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_surname_two',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_surname_two'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_dob_two',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'first_chidren_dob_two'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_name_three',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_name_three'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_surname_three',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_surname_three'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_dob_three',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'first_chidren_dob_three'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_name_four',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_name_four'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_surname_four',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_surname_four'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_dob_four',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'first_chidren_dob_four'
                        ]
                  );
            
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_name_five',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_name_five'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_surname_five',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_surname_five'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_dob_five',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'first_chidren_dob_five'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_name_six',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_name_six'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_surname_six',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'first_chidren_surname_six'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'first_chidren_dob_six',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'first_chidren_dob_six'
                        ]
                  );

              $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_no_of_child',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_no_of_child'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_name_one',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_name_one'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_surname_one',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_surname_one'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_dob_one',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_dob_one'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_name_two',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_name_two'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_surname_two',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_surname_two'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_dob_two',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_dob_two'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_name_three',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_name_three'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_surname_three',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_surname_three'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_dob_three',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_dob_three'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_name_four',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_name_four'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_surname_four',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_surname_four'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_dob_four',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_dob_four'
                        ]
                  );
            
            $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_name_five',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_name_five'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_surname_five',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_surname_five'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_dob_five',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_dob_five'
                        ]
                  );

             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_name_six',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_name_six'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_surname_six',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_surname_six'
                        ]
                  );
            
             $installer->getConnection()->addColumn(
                        $installer->getTable( 'certificato' ),
                        'engaged_chidren_dob_six',
                        [
                              'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                              'nullable' => true,
                              'comment'=> 'engaged_chidren_dob_six'
                        ]
                  );
            }
			 if(version_compare($context->getVersion(), '1.2.16', '<')) {
				 $installer->getConnection()->addIndex(
                    'certificato', //table name
                    'name',    // index name
                    [
                        'name','surname','email','certificato_code','equpiment'   // filed or column name 
                    ],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT //type of index
                );
			 }
			 if(version_compare($context->getVersion(), '1.2.17', '<')) {
			 $installer->getConnection()->addIndex(
                    'certificato',
                    $installer->getIdxName(
                        'certificato',
                        ['name','surname','email','certificato_code','equpiment'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                     ['name','surname','email','certificato_code','equpiment'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			 );
			 }
                  if(version_compare($context->getVersion(), '1.2.19', '<')) {
                        
                              $installer->getConnection()->addColumn(
                                    $installer->getTable( 'certificato' ),
                                    'deactive_date',
                                    [
                                         'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                                         'nullable' => true,
                                        'comment'=> 'deactive_date'
                                    ]
                              );
            }

	}
}