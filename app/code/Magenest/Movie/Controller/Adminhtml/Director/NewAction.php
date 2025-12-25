<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Director;

class NewAction extends AbstractDirector
{
    public function execute()
    {
        return $this->_redirect('magenest_movie/director/edit');
    }
}
