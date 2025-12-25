<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

class ActionsActor extends AbstractRenderer
{
    public function render(DataObject $row): string
    {
        $delUrl  = $this->getUrl('magenest_movie/actor/delete', ['actor_id' => (int)$row->getId()]);

        return

             '<a href="' . $delUrl . '" onclick="return confirm(\'' . __('Delete?') . '\')">' . __('Delete') . '</a>';
    }
}
