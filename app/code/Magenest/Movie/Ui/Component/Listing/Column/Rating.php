<?php
namespace Magenest\Movie\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class Rating extends Column
{
public function prepareDataSource(array $dataSource)
{
    if (!isset($dataSource['data']['items'])) {
        return $dataSource;
    }

    foreach ($dataSource['data']['items'] as &$item) {
        if (!isset($item['rating'])) {
            continue;
        }

        // Điểm gốc (0-10 hoặc 1-10)
        $score = (int)$item['rating'];

        // Clamp cho an toàn (tránh âm / >10)
        $score = max(0, min(10, $score));

        // Quy đổi sang 5 sao:
        // 0-1 => 0 sao
        // 2-3 => 1 sao
        // 4-5 => 2 sao
        // 6-7 => 3 sao
        // 8   => 4 sao
        // 9-10=> 5 sao
        $starsFilled = ($score <= 1) ? 0 : (int)ceil($score / 2);

        // Clamp lần nữa cho chắc
        $starsFilled = max(0, min(5, $starsFilled));

        $starsEmpty = 5 - $starsFilled;

        $starsHtml = str_repeat('&#9733;', $starsFilled) . str_repeat('&#9734;', $starsEmpty);

        $item['rating'] =
            '<span style="color:#ffc107; font-size:16px;">' . $starsHtml . '</span>'
            . ' <span style="color:#666;"> - ' . $score . '/10</span>';
    }

    return $dataSource;
}

}