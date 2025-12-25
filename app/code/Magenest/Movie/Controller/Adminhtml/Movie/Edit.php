<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Magenest\Movie\Model\MovieFactory;

class Edit extends AbstractMovie
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private PageFactory $resultPageFactory,
        private Registry $registry,
        private MovieFactory $movieFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('movie_id');
        $movie = $this->movieFactory->create();
        if ($id) {
            $movie->load($id);
        }
        $this->registry->register('current_movie', $movie);

        $page = $this->resultPageFactory->create();
        $page->setActiveMenu('Magenest_Movie::movie_manage');
        $page->getConfig()->getTitle()->prepend($id ? __('Edit Movie') : __('New Movie'));
        return $page;
    }
}
