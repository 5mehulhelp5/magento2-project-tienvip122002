<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Director;

use Magento\Framework\View\Result\PageFactory;

class Index extends AbstractDirector
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $page = $this->resultPageFactory->create();
        $page->setActiveMenu('Magenest_Movie::director_manage');
        $page->getConfig()->getTitle()->prepend(__('Directors'));
        return $page;
    }
}
