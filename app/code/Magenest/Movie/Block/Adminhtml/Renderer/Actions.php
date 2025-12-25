<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

class Actions extends AbstractRenderer
{
    public function render(DataObject $row): string
    {
        $editUrl = $this->getUrl('magenest_movie/movie/edit', ['movie_id' => (int)$row->getId()]);
        $delUrl  = $this->getUrl('magenest_movie/movie/delete', ['movie_id' => (int)$row->getId()]);

        return '<a href="' . $editUrl . '">' . __('Edit') . '</a>'
            . ' | '
            . '<a href="' . $delUrl . '" onclick="return confirm(\'' . __('Delete?') . '\')">' . __('Delete') . '</a>';
    }
}
