<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Actor;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'Magenest_Movie';
        $this->_controller = 'adminhtml_actor';
        $this->_mode = 'edit';
        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Actor'));
        $this->buttonList->update('back', 'label', __('Back'));
        
        $actorId = $this->getRequest()->getParam('actor_id');
        if ($actorId) {
            $this->buttonList->add(
                'delete',
                [
                    'label' => __('Delete Actor'),
                    'class' => 'delete',
                    'onclick' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to delete this actor?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})'
                ],
                -10
            );
        }
    }
    
    public function getDeleteUrl()
    {
        $actorId = $this->getRequest()->getParam('actor_id');
        return $this->getUrl('*/*/delete', ['actor_id' => $actorId]);
    }

    public function getHeaderText()
    {
        return __('Actor');
    }
}


