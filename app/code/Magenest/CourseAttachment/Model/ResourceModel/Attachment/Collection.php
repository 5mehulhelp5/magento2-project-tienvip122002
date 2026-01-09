<?php
namespace Magenest\CourseAttachment\Model\ResourceModel\Attachment;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Magenest\CourseAttachment\Model\Attachment::class,
            \Magenest\CourseAttachment\Model\ResourceModel\Attachment::class
        );
    }
}