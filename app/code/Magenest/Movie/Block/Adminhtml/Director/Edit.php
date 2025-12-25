<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Director;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'Magenest_Movie';
        $this->_controller = 'adminhtml_director';
        $this->_mode = 'edit';
        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Director'));
        $this->buttonList->update('back', 'label', __('Back'));
        
        $directorId = $this->getRequest()->getParam('director_id');
        if ($directorId) {
            $this->buttonList->add(
                'delete',
                [
                    'label' => __('Delete Director'),
                    'class' => 'delete',
                    'onclick' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to delete this director?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})'
                ],
                -10
            );
        }
    }
    
    public function getDeleteUrl()
    {
        $directorId = $this->getRequest()->getParam('director_id');
        return $this->getUrl('*/*/delete', ['director_id' => $directorId]);
    }

    public function getHeaderText()
    {
        return __('Director');
    }
}


