<?php
declare(strict_types=1);

namespace Magenest\Movie\Model;

use Magento\Framework\Model\AbstractModel;

class Director extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(\Magenest\Movie\Model\ResourceModel\Director::class);
    }
}



