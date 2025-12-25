<?php
declare(strict_types=1);

namespace Magenest\Movie\Model\ResourceModel\Movie;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Magenest\Movie\Model\Movie::class,
            \Magenest\Movie\Model\ResourceModel\Movie::class
        );
    }
}
