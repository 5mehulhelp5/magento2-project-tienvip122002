<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magento\Backend\App\Action;

abstract class AbstractMovie extends Action
{
    const ADMIN_RESOURCE = 'Magenest_Movie::movie_manage';
}
