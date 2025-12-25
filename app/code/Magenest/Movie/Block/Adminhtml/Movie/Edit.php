<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Movie;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'Magenest_Movie';
        $this->_controller = 'adminhtml_movie';
        $this->_mode = 'edit';
        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Movie'));
        $this->buttonList->update('back', 'label', __('Back'));
        
        $movieId = $this->getRequest()->getParam('movie_id');
        if ($movieId) {
            $this->buttonList->add(
                'delete',
                [
                    'label' => __('Delete Movie'),
                    'class' => 'delete',
                    'onclick' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to delete this movie?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})'
                ],
                -10
            );
        }
    }
    
    public function getDeleteUrl()
    {
        $movieId = $this->getRequest()->getParam('movie_id');
        return $this->getUrl('*/*/delete', ['movie_id' => $movieId]);
    }

    public function getHeaderText()
    {
        return __('Movie');
    }
}
