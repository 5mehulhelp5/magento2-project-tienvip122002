<?php
namespace Magenest\CourseAttachment\Model;

use Magento\Framework\Model\AbstractModel;

class Attachment extends AbstractModel
{
    /**
     * Khởi tạo Model
     * Định nghĩa ResourceModel nào sẽ xử lý dữ liệu cho Model này
     */
    protected function _construct()
    {
        $this->_init(\Magenest\CourseAttachment\Model\ResourceModel\Attachment::class);
    }
}