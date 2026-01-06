<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Container1;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    public const ADMIN_RESOURCE = 'Magenest_Movie::1st';

    public function __construct(
        Action\Context $context,
        private readonly PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $page = $this->resultPageFactory->create();
        $page->setActiveMenu('Magenest_Movie::1st');
        $page->getConfig()->getTitle()->prepend(__('1st'));
        return $page;
    }
}
