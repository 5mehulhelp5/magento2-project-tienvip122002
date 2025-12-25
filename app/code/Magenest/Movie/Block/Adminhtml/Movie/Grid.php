<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Movie;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magenest\Movie\Model\ResourceModel\Movie\CollectionFactory;

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
        $this->setDefaultSort('movie_id');
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
        $this->addColumn('movie_id', [
            'header' => __('ID'),
            'index'  => 'movie_id',
            'type'   => 'number'
        ]);

        $this->addColumn('name', [
            'header' => __('Name'),
            'index'  => 'name',
        ]);

        $this->addColumn('rating', [
            'header' => __('Rating'),
            'index'  => 'rating',
            'type'   => 'number'
        ]);

        $this->addColumn('director_id', [
            'header' => __('Director ID'),
            'index'  => 'director_id',
            'type'   => 'number'
        ]);

        $this->addColumn('description', [
            'header' => __('Description'),
            'index'  => 'description',
            'truncate' => 80,
        ]);

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('magenest_movie/movie/edit', ['movie_id' => $row->getId()]);
    }
}
