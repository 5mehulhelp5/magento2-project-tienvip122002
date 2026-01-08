<?php
declare(strict_types=1);

namespace Magenest\OrderExport\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory as StatusCollectionFactory;

class Export extends Template
{
    protected $_template = 'Magenest_OrderExport::export.phtml';
    
    protected $statusCollectionFactory;

    public function __construct(
        Context $context,
        StatusCollectionFactory $statusCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->statusCollectionFactory = $statusCollectionFactory;
    }

    /**
     * Get export CSV URL
     */
    public function getExportUrl(): string
    {
        return $this->getUrl('orderexport/export/csv');
    }

    /**
     * Get all order statuses
     */
    public function getOrderStatuses(): array
    {
        $statuses = $this->statusCollectionFactory->create()->toOptionArray();
        return $statuses;
    }
}