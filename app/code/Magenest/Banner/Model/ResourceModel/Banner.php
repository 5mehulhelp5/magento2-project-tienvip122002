<?php
namespace Magenest\Banner\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Banner extends AbstractDb
{
    /**
     * Hàm khởi tạo
     */
    protected function _construct()
    {
        // Tham số 1: Tên bảng trong database (magenest_banner)
        // Tham số 2: Tên cột khóa chính (banner_id) - Phải trùng với db_schema.xml
        $this->_init('magenest_banner', 'banner_id');
    }
}