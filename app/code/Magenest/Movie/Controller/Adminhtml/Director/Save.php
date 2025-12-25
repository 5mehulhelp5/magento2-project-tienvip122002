<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Director;

use Magenest\Movie\Model\DirectorFactory;

class Save extends AbstractDirector
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private DirectorFactory $directorFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $data = (array)$this->getRequest()->getPostValue();
        if (!$data) {
            return $this->_redirect('magenest_movie/director/index');
        }

        $id = (int)($data['director_id'] ?? 0);
        $director = $this->directorFactory->create();
        if ($id) {
            $director->load($id);
        }

        $director->setData('name', (string)($data['name'] ?? ''));

        try {
            $director->save();
            $this->messageManager->addSuccessMessage(__('Director saved.'));
            return $this->_redirect('magenest_movie/director/index');
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__('Save failed: %1', $e->getMessage()));
            return $this->_redirect('magenest_movie/director/edit', ['director_id' => $id]);
        }
    }
}
