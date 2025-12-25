<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magenest\Movie\Model\MovieFactory;

class Delete extends AbstractMovie
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private MovieFactory $movieFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('movie_id');
        if (!$id) {
            $this->messageManager->addErrorMessage(__('Missing movie id.'));
            return $this->_redirect('magenest_movie/movie/index');
        }

        try {
            $movie = $this->movieFactory->create()->load($id);
            if (!$movie->getId()) {
                throw new \RuntimeException('Movie not found.');
            }
            $movie->delete();
            $this->messageManager->addSuccessMessage(__('Movie deleted.'));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__('Delete failed: %1', $e->getMessage()));
        }

        return $this->_redirect('magenest_movie/movie/index');
    }
}
