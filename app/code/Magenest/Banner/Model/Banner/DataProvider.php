<?php
namespace Magenest\Banner\Model\Banner;

use Magenest\Banner\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class DataProvider extends AbstractDataProvider
{
    protected $loadedData;
    protected $dataPersistor;
    protected $storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        foreach ($items as $banner) {
            $data = $banner->getData();
            
            // Nếu trong DB có tên ảnh, ta phải dựng lại cấu trúc mảng cho UI Component
            if (isset($data['image'])) {
                $imageName = $data['image'];
                unset($data['image']); // Xóa string cũ đi
                
                $data['image'][0]['name'] = $imageName;
                $data['image'][0]['url'] = $mediaUrl . 'banner/' . $imageName; // Đường dẫn ảnh thật
            }

            $this->loadedData[$banner->getId()] = $data;
        }

        return $this->loadedData;
    }
}