<?php

namespace Calderoni\Faber\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
	
	protected $resultJsonFactory;
	protected $faberAuth;
	protected $faberHeader;
	protected $faberRow;
	protected $faberDocument;
	protected $faberHelper;
	
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Calderoni\Faber\Model\Api\Authenticate $faberAuth,
		\Calderoni\Faber\Model\Api\Header $faberHeader,
		\Calderoni\Faber\Model\Api\Row $faberRow,
		\Calderoni\Faber\Model\Api\Document $faberDocument,
		\Calderoni\Faber\Helper\Data $faberHelper
	) {
		parent::__construct($context);
		$this->resultJsonFactory = $resultJsonFactory;
		$this->faberAuth = $faberAuth;
		$this->faberHeader = $faberHeader;
		$this->faberRow = $faberRow;
		$this->faberDocument = $faberDocument;
		$this->faberHelper = $faberHelper;
	}

    public function execute()
    {
		echo "<pre>";
		//Authenticate Test
		//$this->faberAuth->authenticate();
		
		//Header test
		//$addfields = array("DataOrdine"=>"20180711","NumeroOrdine"=>"1","Nome"=>"Paolo","Cognome"=>"Belloni","Templates_lkp"=>"9","Indirizzo"=>"Via Ettore Bugatti 15","Citta"=>"Milano","Provincia"=>"Mi","Nazione"=>"Italia","CodiceFiscale"=>"BLLPLA84H14F205D","DataNascita"=>"19840614");
		//$this->faberHeader->addHeader( $addfields );
		//$this->faberHeader->editHeader( 219,$addfields );
		//$this->faberHeader->deleteHeader( 219 );
		//$this->faberHeader->searchHeader( array(["Id","eq","219"]) );
		
		//Row test
		$addfields = array("HeaderId"=>"219","Codice"=>"C9999","Quantita"=>1,"Carato"=>"0.8","Colore"=>"F","Taglio"=>"O","Fluorescenza"=>"D","Prezzo"=>"1400,00");
		//$this->faberRow->addRow( $addfields );
		//$this->faberRow->editRow( 201,$addfields );
		//$this->faberRow->deleteRow( 201 );
		//$this->faberRow->searchRow( array(["Id","eq","201"]) );
		
		//Document test
		//$this->faberDocument->compose(222);
		//$this->faberDocument->create([222]);
		
		exit;
		
    }
}