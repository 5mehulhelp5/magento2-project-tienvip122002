<?php
declare(strict_types=1);

namespace Magenest\Movie\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Director extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('magenest_director', 'director_id');
    }
}



