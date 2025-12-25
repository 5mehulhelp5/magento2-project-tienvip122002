<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Actor;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Magenest\Movie\Model\ActorFactory;

class Edit extends AbstractActor
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private PageFactory $resultPageFactory,
        private Registry $registry,
        private ActorFactory $actorFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('actor_id');
        $actor = $this->actorFactory->create();
        if ($id) {
            $actor->load($id);
        }
        $this->registry->register('current_actor', $actor);

        $page = $this->resultPageFactory->create();
        $page->setActiveMenu('Magenest_Movie::actor_manage');
        $page->getConfig()->getTitle()->prepend($id ? __('Edit Actor') : __('New Actor'));
        return $page;
    }
}
