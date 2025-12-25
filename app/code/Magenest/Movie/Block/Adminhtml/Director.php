<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Director extends Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_director';
        $this->_blockGroup = 'Magenest_Movie';
        $this->_headerText = __('Directors');
        $this->_addButtonLabel = __('New Director');
        parent::_construct();
    }

    protected function _getAddNewButtonUrl()
    {
        return $this->getUrl('magenest_movie/director/new');
    }
}
