<?php
namespace Magenest\Banner\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Thumbnail extends Column
{
    protected $storeManager;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            $fieldName = 'image'; // Tên cột trong Database

            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$fieldName])) {
                    // Tạo đường dẫn full: pub/media/banner/ten-file.jpg
                    $url = $mediaUrl . 'banner/' . $item[$fieldName];
                    
                    // Gán vào đúng format cho UI Grid
                    $item[$fieldName . '_src'] = $url;
                    $item[$fieldName . '_alt'] = $item['name'] ?? 'Banner';
                    $item[$fieldName . '_link'] = $this->context->getUrl(
                        'magenest_banner/banner/edit',
                        ['banner_id' => $item['banner_id']]
                    );
                    $item[$fieldName . '_orig_src'] = $url;
                }
            }
        }
        return $dataSource;
    }
}