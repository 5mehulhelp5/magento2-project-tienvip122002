<?php
namespace Magenest\Banner\Controller\Adminhtml\Banner;
use Magento\Backend\App\Action;

class NewAction extends Action
{
    public function execute()
    {
        $this->_forward('edit');
    }
}