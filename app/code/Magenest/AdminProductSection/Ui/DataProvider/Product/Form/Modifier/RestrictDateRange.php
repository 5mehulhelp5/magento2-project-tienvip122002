<?php
declare(strict_types=1);

namespace Magenest\AdminProductSection\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;

class RestrictDateRange extends AbstractModifier
{
    public function __construct(
        private readonly ArrayManager $arrayManager
    ) {
    }

    public function modifyData(array $data): array
    {
        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        $attributes = ['magenest_from_date', 'magenest_to_date'];

        foreach ($attributes as $attributeCode) {
            $path = $this->arrayManager->findPath($attributeCode, $meta, null, 'children');

            if ($path) {
                $meta = $this->arrayManager->merge(
                    $path . '/arguments/data/config',
                    $meta,
                    [
                        'component' => 'Magenest_AdminProductSection/js/form/element/date-restricted',
                        'options' => [
                            'showsTime' => true,
                            'timeFormat' => 'HH:mm',
                        ],
                        'dateFormat' => 'yyyy-MM-dd',
                        // We enforce 'datetime' behavior
                        'dataType' => 'datetime',
                        'formElement' => 'date'
                    ]
                );
            }
        }

        return $meta;
    }
}
