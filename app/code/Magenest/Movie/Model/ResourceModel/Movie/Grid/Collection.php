<?php
declare(strict_types=1);

namespace Magenest\Movie\Model\ResourceModel\Movie\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult{
    protected function _initSelect()
    {
        parent::_initSelect();

        // Lọc theo name
        $this->addFilterToMap('fulltext', 'main_table.name');

        return $this;
    }   
}
