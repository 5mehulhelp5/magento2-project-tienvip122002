<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Movie;

class NewAction extends AbstractMovie
{
    public function execute()
    {
        return $this->_redirect('magenest_movie/movie/edit');
    }
}
