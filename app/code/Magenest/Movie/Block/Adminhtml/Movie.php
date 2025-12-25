<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Movie extends Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_movie';
        $this->_blockGroup = 'Magenest_Movie';
        $this->_headerText = __('Movies');
        $this->_addButtonLabel = __('New Movie');
        parent::_construct();
    }

    protected function _getAddNewButtonUrl()
    {
        return $this->getUrl('magenest_movie/movie/new');
    }
}
