<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\App\ResourceConnection;

class RowCount extends Field
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        private ResourceConnection $resource,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    protected function _getElementHtml(AbstractElement $element): string
    {
        $fieldConfig = $element->getFieldConfig();
        $table = (string)($fieldConfig['table'] ?? '');

        $count = 0;
        if ($table !== '') {
            $conn = $this->resource->getConnection();
            $tableName = $this->resource->getTableName($table);
            $count = (int)$conn->fetchOne("SELECT COUNT(*) FROM {$tableName}");
        }

        // Set value + make it readonly/disabled so it won't be saved/edited
        $element->setValue((string)$count);
        $element->setReadonly(true, true);
        $element->setDisabled('disabled');

        return $element->getElementHtml();
    }
}
