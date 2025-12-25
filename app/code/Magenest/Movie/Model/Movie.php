<?php
declare(strict_types=1);

namespace Magenest\Movie\Model;

use Magento\Framework\Model\AbstractModel;

class Movie extends AbstractModel
{

    /** để Magento tự dispatch: magenest_movie_save_before/after */
    protected $_eventPrefix = 'magenest_movie';
    protected $_eventObject = 'movie';
    protected function _construct()
    {
        $this->_init(\Magenest\Movie\Model\ResourceModel\Movie::class);
    }
}
