<?php
namespace Magenest\Banner\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Banner Management'));
        return $resultPage;
    }

    // Quyền truy cập (ACL)
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Banner::banner');
    }
}