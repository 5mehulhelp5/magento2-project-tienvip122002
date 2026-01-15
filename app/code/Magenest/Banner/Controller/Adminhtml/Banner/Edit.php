<?php
namespace Magenest\Banner\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        // 1. Load layout
        $resultPage = $this->resultPageFactory->create();
        
        // 2. Set Title
        $id = $this->getRequest()->getParam('banner_id');
        $title = $id ? __('Edit Banner') : __('New Banner');
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }
}