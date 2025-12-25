<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Actor extends Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_actor';
        $this->_blockGroup = 'Magenest_Movie';
        $this->_headerText = __('Actors');
        $this->_addButtonLabel = __('New Actor');
        parent::_construct();
    }

    protected function _getAddNewButtonUrl()
    {
        return $this->getUrl('magenest_movie/actor/new');
    }
}
