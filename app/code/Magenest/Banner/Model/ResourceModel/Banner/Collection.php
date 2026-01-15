<?php
namespace Magenest\Banner\Model\ResourceModel\Banner;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Cấu hình Collection
     */
    protected function _construct()
    {
        // Khai báo Model và ResourceModel tương ứng
        $this->_init(
            \Magenest\Banner\Model\Banner::class,
            \Magenest\Banner\Model\ResourceModel\Banner::class
        );
    }
}