<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magento\Framework\View\Result\PageFactory;

class Index extends AbstractMovie
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
        $page->setActiveMenu('Magenest_Movie::movie_manage');
        $page->getConfig()->getTitle()->prepend(__('Movies'));
        return $page;
    }
}
