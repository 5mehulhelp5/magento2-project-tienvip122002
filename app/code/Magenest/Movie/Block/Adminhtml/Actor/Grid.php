<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Actor;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magenest\Movie\Model\ResourceModel\Actor\CollectionFactory;

class Grid extends Extended
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        private CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('magenest_movie_grid');
        $this->setDefaultSort('actor_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = $this->collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('actor_id', [
            'header' => __('ID'),
            'index'  => 'actor_id',
            'type'   => 'number'
        ]);

        $this->addColumn('name', [
            'header' => __('Name'),
            'index'  => 'name',
        ]);


        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('magenest_movie/actor/edit', ['actor_id' => $row->getId()]);
    }
}
