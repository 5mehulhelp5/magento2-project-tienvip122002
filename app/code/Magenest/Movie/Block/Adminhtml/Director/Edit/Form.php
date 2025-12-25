<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Director\Edit;

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
        /** @var \Magenest\Movie\Model\Director $director */
        $director = $this->_coreRegistry->registry('current_director');

        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('magenest_movie/director/save'),
                'method' => 'post'
            ]
        ]);
        $form->setUseContainer(true);

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Director Information')]);

        if ($director && $director->getId()) {
            $fieldset->addField('director_id', 'hidden', ['name' => 'director_id']);
        }

        $fieldset->addField('name', 'text', [
            'name' => 'name',
            'label' => __('Name'),
            'required' => true,
        ]);

        if ($director) {
            $form->setValues($director->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }
}



