<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Actor\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;

class Form extends Generic
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Registry $registry,
        FormFactory $formFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        /** @var \Magenest\Movie\Model\Actor $actor */
        $actor = $this->_coreRegistry->registry('current_actor');

        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('magenest_movie/actor/save'),
                'method' => 'post'
            ]
        ]);
        $form->setUseContainer(true);

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Actor Information')]);

        if ($actor && $actor->getId()) {
            $fieldset->addField('actor_id', 'hidden', ['name' => 'actor_id']);
        }

        $fieldset->addField('name', 'text', [
            'name' => 'name',
            'label' => __('Name'),
            'required' => true,
        ]);

        if ($actor) {
            $form->setValues($actor->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }
}



