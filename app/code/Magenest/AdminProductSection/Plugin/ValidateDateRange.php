<?php
declare(strict_types=1);

namespace Magenest\AdminProductSection\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

/**
 * Validate date range - only allow days 8-12 in any month
 */
class ValidateDateRange
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Validate date range before saving product
     *
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $product
     * @param bool $saveOptions
     * @return array
     * @throws LocalizedException
     */
    public function beforeSave(
        ProductRepositoryInterface $subject,
        ProductInterface $product,
        $saveOptions = false
    ): array {
        $this->validateDateAttributes($product);
        return [$product, $saveOptions];
    }

    /**
     * Validate custom date attributes
     *
     * @param ProductInterface $product
     * @throws LocalizedException
     */
    private function validateDateAttributes(ProductInterface $product): void
    {
        $fromDate = $product->getCustomAttribute('magenest_from_date');
        $toDate = $product->getCustomAttribute('magenest_to_date');

        // Skip if both are empty
        if (!$fromDate && !$toDate) {
            return;
        }

        $fromValue = $fromDate ? $fromDate->getValue() : null;
        $toValue = $toDate ? $toDate->getValue() : null;

        // If one is set, both must be set
        if (($fromValue && !$toValue) || (!$fromValue && $toValue)) {
            throw new LocalizedException(
                __('Both From Date and To Date must be set together.')
            );
        }

        // Parse dates
        $fromTimestamp = strtotime($fromValue);
        $toTimestamp = strtotime($toValue);

        if ($fromTimestamp === false || $toTimestamp === false) {
            throw new LocalizedException(
                __('Invalid date format. Please use a valid date.')
            );
        }

        // Validate: To Date >= From Date
        if ($toTimestamp < $fromTimestamp) {
            throw new LocalizedException(
                __('To Date must be greater than or equal to From Date.')
            );
        }

        // Validate: Both dates must be days 8-12
        $fromDay = (int)date('j', $fromTimestamp);
        $toDay = (int)date('j', $toTimestamp);

        if ($fromDay < 8 || $fromDay > 12) {
            throw new LocalizedException(
                __('From Date must be between day 8 and 12 of any month. You selected day %1.', $fromDay)
            );
        }

        if ($toDay < 8 || $toDay > 12) {
            throw new LocalizedException(
                __('To Date must be between day 8 and 12 of any month. You selected day %1.', $toDay)
            );
        }

        $this->logger->info('Date validation passed', [
            'from_date' => $fromValue,
            'to_date' => $toValue,
            'from_day' => $fromDay,
            'to_day' => $toDay
        ]);
    }
}