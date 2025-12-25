<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Actor;

use Magenest\Movie\Model\ActorFactory;

class Delete extends AbstractActor
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private ActorFactory $actorFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('actor_id');
        if (!$id) {
            $this->messageManager->addErrorMessage(__('Missing actor id.'));
            return $this->_redirect('magenest_movie/actor/index');
        }

        try {
            $actor = $this->actorFactory->create()->load($id);
            if (!$actor->getId()) {
                throw new \RuntimeException('Actor not found.');
            }
            $actor->delete();
            $this->messageManager->addSuccessMessage(__('Actor deleted.'));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__('Delete failed: %1', $e->getMessage()));
        }

        return $this->_redirect('magenest_movie/actor/index');
    }
}
