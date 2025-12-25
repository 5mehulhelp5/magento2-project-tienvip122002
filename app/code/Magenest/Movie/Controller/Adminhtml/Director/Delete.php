<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Director;

use Magenest\Movie\Model\DirectorFactory;

class Delete extends AbstractDirector
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private DirectorFactory $directorFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('director_id');
        if (!$id) {
            $this->messageManager->addErrorMessage(__('Missing director id.'));
            return $this->_redirect('magenest_movie/director/index');
        }

        try {
            $director = $this->directorFactory->create()->load($id);
            if (!$director->getId()) {
                throw new \RuntimeException('Director not found.');
            }
            $director->delete();
            $this->messageManager->addSuccessMessage(__('Director deleted.'));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__('Delete failed: %1', $e->getMessage()));
        }

        return $this->_redirect('magenest_movie/director/index');
    }
}
