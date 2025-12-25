<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Actor;

class NewAction extends AbstractActor
{
    public function execute()
    {
        return $this->_redirect('magenest_movie/actor/edit');
    }
}
