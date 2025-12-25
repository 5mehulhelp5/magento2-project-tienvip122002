<?php
declare(strict_types=1);

namespace Magenest\Movie\Controller\Adminhtml\Actor;

use Magento\Backend\App\Action;

abstract class AbstractActor extends Action
{
    const ADMIN_RESOURCE = 'Magenest_Movie::actor_manage';
}
