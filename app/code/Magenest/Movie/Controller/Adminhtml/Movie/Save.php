<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magenest\Movie\Model\MovieFactory;

class Save extends AbstractMovie
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private MovieFactory $movieFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $data = (array)$this->getRequest()->getPostValue();
        if (!$data) {
            return $this->_redirect('magenest_movie/movie/index');
        }

        $id = (int)($data['movie_id'] ?? 0);
        $movie = $this->movieFactory->create();
        if ($id) {
            $movie->load($id);
        }

        $movie->setData('name', (string)($data['name'] ?? ''));
        $movie->setData('description', (string)($data['description'] ?? ''));
        $movie->setData('rating', (int)($data['rating'] ?? 0));
        $movie->setData('director_id', (int)($data['director_id'] ?? 0));

        try {
            $movie->save();
            $this->messageManager->addSuccessMessage(__('Movie saved.'));
            return $this->_redirect('magenest_movie/movie/index');
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__('Save failed: %1', $e->getMessage()));
            return $this->_redirect('magenest_movie/movie/edit', ['movie_id' => $id]);
        }
    }
}
