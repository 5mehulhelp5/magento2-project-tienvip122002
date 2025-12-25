<?php
declare(strict_types=1);

namespace Magenest\Movie\Plugin\Checkout;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\ConfigurableProduct\Block\Cart\Item\Renderer\Configurable as Subject;

class UseChildInCartRenderer
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function afterGetProductName(Subject $subject, string $result): string
    {
        $child = $this->getChildProduct($subject->getItem());
        return $child ? (string)$child->getName() : $result;
    }

    public function afterGetProductForThumbnail(Subject $subject, $result)
    {
        $child = $this->getChildProduct($subject->getItem());
        return $child ?: $result;
    }

    private function getChildProduct(Item $item)
    {
        $opt = $item->getOptionByCode('simple_product');
        if (!$opt) return null;

        // đôi khi option đã giữ sẵn product object
        $p = $opt->getProduct();
        if ($p && $p->getId()) return $p;

        // fallback: value thường là ID của simple
        $id = (int)$opt->getValue();
        if ($id <= 0) return null;

        try {
            return $this->productRepository->getById($id);
        } catch (\Throwable) {
            return null;
        }
    }
}
