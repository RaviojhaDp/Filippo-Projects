<?php
 
namespace Dolphin\Certificato\Ui\Component\Listing\Grid\Column;
 
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
 
class Action extends Column
{
    /** Url path */
    const ROW_EDIT_URL = 'certificato/index/addrow';
    const ROW_DEACTIVE_URL = 'certificato/index/deactive';
    /** @var UrlInterface */
    protected $_urlBuilder;
 
    /**
     * @var string
     */
    private $_editUrl;
 
    /**
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     * @param string             $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::ROW_EDIT_URL,
        $deactiveUrl = self::ROW_DEACTIVE_URL
    ) 
    {
        $this->_urlBuilder = $urlBuilder;
        $this->_editUrl = $editUrl;
        $this->_deactiveUrl = $deactiveUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
 
    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                    
                //echo "<pre>";
                //print_r($item);

                if (isset($item['certificato_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->_urlBuilder->getUrl(
                            $this->_editUrl, 
                            ['id' => $item['certificato_id']]
                        ),
                        'label' => __('Edit'),
                    ];
                if (isset($item['status']) && $item['status'] != '4' ) {
                    $item[$name]['deactive'] = [
                        'href' => $this->_urlBuilder->getUrl(
                            $this->_deactiveUrl, 
                            ['id' => $item['certificato_id']]
                        ),
                        'label' => __('Deactive'),
                        'confirm' => [
                        'title' => __('Deactivate warranty'),
                        'message' => __('Are you sure?')
                     ]
                    ];
                  }
                }
            }
        }
 
        return $dataSource;
    }
}