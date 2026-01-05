<?php
declare(strict_types=1);

namespace Magenest\OddEvenOrder\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class OddEven extends Column
{
    public function prepareDataSource(array $dataSource): array
    {

        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            // sales_order grid thường có entity_id là Order ID nội bộ
            if (!isset($item['entity_id'])) {
                continue;
            }

            $orderId = (int)$item['entity_id'];
            $isEven  = ($orderId % 2) === 0;

            // Magento UI Library: có thể dùng một số class khác dạng class="message message-success success">
            // (Odd = critical -> dùng error style; Even = success/notice -> dùng success style)
            // $item['odd_even'] = $isEven ? 'Even' : 'Odd';
            if ($isEven) {
                $item['odd_even'] = '<span class="grid-severity-notice"><span>SUCCESS</span></span>';
            } else {
                $item['odd_even'] = '<span class="grid-severity-critical"><span>ERROR</span></span>';
            }
        }

        return $dataSource;
    }
}
