<?php
declare(strict_types=1);

namespace Magenest\Movie\Observer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ForceCustomerFirstname implements ObserverInterface
{
    private static bool $running = false;

    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {
    }

    public function execute(Observer $observer): void
    {
        if (self::$running) {
            return;
        }

        $customer = $observer->getData('customer_data_object'); // CustomerInterface
        if (!$customer) {
            return;
        }

        if ((string)$customer->getFirstname() === 'Magenest') {
            return;
        }

        self::$running = true;
        try {
            $customer->setFirstname('Magenest');
            $this->customerRepository->save($customer);
        } finally {
            self::$running = false;
        }
    }
}
