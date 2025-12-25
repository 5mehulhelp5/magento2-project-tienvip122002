<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Director;

use Magento\Backend\App\Action;

abstract class AbstractDirector extends Action
{
    const ADMIN_RESOURCE = 'Magenest_Movie::director_manage';
}
