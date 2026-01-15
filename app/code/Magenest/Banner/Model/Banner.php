<?php
namespace Magenest\Banner\Model;

use Magento\Framework\Model\AbstractModel;

class Banner extends AbstractModel
{
    /**
     * Hàm khởi tạo
     * Khai báo ResourceModel tương ứng
     */
    protected function _construct()
    {
        $this->_init(\Magenest\Banner\Model\ResourceModel\Banner::class);
    }
}