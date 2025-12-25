<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Movie\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magenest\Movie\Model\ResourceModel\Director\CollectionFactory as DirectorCollectionFactory;

class Form extends Generic
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Registry $registry,
        FormFactory $formFactory,
        private DirectorCollectionFactory $directorCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        /** @var \Magenest\Movie\Model\Movie $movie */
        $movie = $this->_coreRegistry->registry('current_movie');

        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('magenest_movie/movie/save'),
                'method' => 'post'
            ]
        ]);
        $form->setUseContainer(true);

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Movie Information')]);

        if ($movie && $movie->getId()) {
            $fieldset->addField('movie_id', 'hidden', ['name' => 'movie_id']);
        }

        $fieldset->addField('name', 'text', [
            'name' => 'name',
            'label' => __('Name'),
            'required' => true,
        ]);

        $fieldset->addField('description', 'textarea', [
            'name' => 'description',
            'label' => __('Description'),
        ]);

        $fieldset->addField('rating', 'text', [
            'name' => 'rating',
            'label' => __('Rating'),
        ]);

        // director select
        $options = [];
        foreach ($this->directorCollectionFactory->create() as $d) {
            $options[] = ['value' => (int)$d->getId(), 'label' => (string)$d->getData('name')];
        }

        $fieldset->addField('director_id', 'select', [
            'name' => 'director_id',
            'label' => __('Director'),
            'values' => $options,
        ]);

        if ($movie) {
            $form->setValues($movie->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
