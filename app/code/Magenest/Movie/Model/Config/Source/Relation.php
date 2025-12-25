<?php
declare(strict_types=1);

namespace Magenest\Movie\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Relation implements ArrayInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 1, 'label' => 'show'],
            ['value' => 2, 'label' => 'not-show'],
        ];
    }
}
