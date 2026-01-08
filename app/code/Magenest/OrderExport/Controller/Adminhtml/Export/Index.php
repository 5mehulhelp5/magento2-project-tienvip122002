<?php
declare(strict_types=1);

namespace Magenest\OrderExport\Controller\Adminhtml\Export;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Display export page
 */
class Index extends Action
{
    const ADMIN_RESOURCE = 'Magenest_OrderExport::export';

    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_OrderExport::export');
        $resultPage->getConfig()->getTitle()->prepend(__('Export Order Items'));
        
        return $resultPage;
    }
}