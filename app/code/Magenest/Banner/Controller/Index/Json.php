<?php
namespace Magenest\Banner\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magenest\Banner\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class Json extends Action
{
    protected $resultJsonFactory;
    protected $collectionFactory;
    protected $storeManager;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        
        // 1. Lấy tất cả banner đang Enable
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', 1);

        // 2. Lấy Base URL Media
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        $data = [];
        foreach ($collection as $banner) {
            $item = $banner->getData();
            // Xử lý full đường dẫn ảnh
            if (isset($item['image'])) {
                $item['image_url'] = $mediaUrl . 'banner/' . $item['image'];
            }
            $data[] = $item;
        }

        // 3. Trả về JSON
        return $result->setData($data);
    }
}