<?php
declare(strict_types=1);

namespace Magenest\Movie\Plugin\MiniCart;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Checkout\CustomerData\DefaultItem as Subject;
use Magento\Framework\Escaper;
use Magento\Quote\Model\Quote\Item;

class UseChildForConfigurableItem
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ImageHelper $imageHelper,
        private Escaper $escaper
    ) {}

    public function afterGetItemData(Subject $subject, array $result, Item $item): array
    {
        // Chỉ xử lý với configurable product
        if (!$item->getProduct() || $item->getProduct()->getTypeId() !== 'configurable') {
            return $result;
        }

        // Lấy child product giống như UseChildInCartRenderer
        $child = $this->getChildProduct($item);
        
        if (!$child) {
            return $result;
        }

        // Thay đổi tên sản phẩm con trong mini cart popup (escape HTML như DefaultItem)
        $childName = (string)$child->getName();
        $result['product_name'] = $this->escaper->escapeHtml($childName);
        $result['product_sku']  = (string)$child->getSku();

        // Thay đổi ảnh sản phẩm con trong mini cart popup
        try {
            $imageHelper = $this->imageHelper->init($child, 'mini_cart_product_thumbnail');
            
            // Cập nhật product_image
            $childName = (string)$child->getName();
            if (isset($result['product_image']) && is_array($result['product_image'])) {
                $result['product_image']['src'] = $imageHelper->getUrl();
                $result['product_image']['alt'] = $childName;
                $result['product_image']['width'] = $imageHelper->getWidth();
                $result['product_image']['height'] = $imageHelper->getHeight();
            } else {
                // Tạo mới product_image nếu chưa có
                $result['product_image'] = [
                    'src' => $imageHelper->getUrl(),
                    'alt' => $childName,
                    'width' => $imageHelper->getWidth(),
                    'height' => $imageHelper->getHeight(),
                ];
            }
        } catch (\Exception $e) {
            // Nếu có lỗi khi load ảnh, vẫn giữ nguyên kết quả gốc
        }

        return $result;
    }

    private function getChildProduct(Item $item)
    {
        // Giống như UseChildInCartRenderer
        $opt = $item->getOptionByCode('simple_product');
        if (!$opt) {
            return null;
        }

        // Đôi khi option đã giữ sẵn product object
        $p = $opt->getProduct();
        if ($p && $p->getId()) {
            return $p;
        }

        // Fallback: value thường là ID của simple
        $id = (int)$opt->getValue();
        if ($id <= 0) {
            return null;
        }

        try {
            return $this->productRepository->getById($id);
        } catch (\Throwable) {
            return null;
        }
    }
}
