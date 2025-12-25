<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Actor;

use Magenest\Movie\Model\ActorFactory;

class Save extends AbstractActor
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private ActorFactory $actorFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $data = (array)$this->getRequest()->getPostValue();
        if (!$data) {
            return $this->_redirect('magenest_movie/actor/index');
        }

        $id = (int)($data['actor_id'] ?? 0);
        $actor = $this->actorFactory->create();
        if ($id) {
            $actor->load($id);
        }

        $actor->setData('name', (string)($data['name'] ?? ''));

        try {
            $actor->save();
            $this->messageManager->addSuccessMessage(__('Actor saved.'));
            return $this->_redirect('magenest_movie/actor/index');
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__('Save failed: %1', $e->getMessage()));
            return $this->_redirect('magenest_movie/actor/edit', ['actor_id' => $id]);
        }
    }
}
