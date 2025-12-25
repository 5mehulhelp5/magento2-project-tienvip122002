<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Director;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Magenest\Movie\Model\DirectorFactory;

class Edit extends AbstractDirector
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private PageFactory $resultPageFactory,
        private Registry $registry,
        private DirectorFactory $directorFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('director_id');
        $director = $this->directorFactory->create();
        if ($id) {
            $director->load($id);
        }
        $this->registry->register('current_director', $director);

        $page = $this->resultPageFactory->create();
        $page->setActiveMenu('Magenest_Movie::director_manage');
        $page->getConfig()->getTitle()->prepend($id ? __('Edit Director') : __('New Director'));
        return $page;
    }
}
