<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Container2;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\ResourceConnection;

class Stats extends Template
{
    public function __construct(
        Context $context,
        private readonly ResourceConnection $resource,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    private function countTable(string $table): int
    {
        $connection = $this->resource->getConnection();
        $tableName = $this->resource->getTableName($table);

        $sql = sprintf('SELECT COUNT(*) FROM %s', $tableName);
        return (int) $connection->fetchOne($sql);
    }

    public function getCustomersCount(): int
    {
        return $this->countTable('customer_entity');
    }

    public function getProductsCount(): int
    {
        return $this->countTable('catalog_product_entity');
    }

    public function getOrdersCount(): int
    {
        return $this->countTable('sales_order');
    }

    public function getInvoicesCount(): int
    {
        return $this->countTable('sales_invoice');
    }

    public function getCreditmemosCount(): int
    {
        return $this->countTable('sales_creditmemo');
    }
}
