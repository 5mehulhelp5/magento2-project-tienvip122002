<?php
declare(strict_types=1);

namespace Magenest\Movie\Observer\Admin;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ForceMovieRatingZero implements ObserverInterface
{
    public function execute(Observer $observer): void
    {
        /** @var \Magenest\Movie\Model\Movie|null $movie */
        $movie = $observer->getData('movie'); // do $_eventObject='movie'
        if (!$movie) {
            return;
        }

        // ép rating = 0 trước khi save DB
        $movie->setData('rating', 0);
    }
}
