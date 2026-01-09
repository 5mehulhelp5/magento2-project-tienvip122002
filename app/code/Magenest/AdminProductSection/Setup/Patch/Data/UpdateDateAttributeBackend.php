<?php
declare(strict_types=1);

namespace Magenest\AdminProductSection\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magenest\AdminProductSection\Model\Attribute\Backend\DateRange;

class UpdateDateAttributeBackend implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly EavSetupFactory $eavSetupFactory
    ) {
    }

    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $entityType = Product::ENTITY;
        $attributes = ['magenest_from_date', 'magenest_to_date'];

        foreach ($attributes as $attributeCode) {
            if ($eavSetup->getAttributeId($entityType, $attributeCode)) {
                $eavSetup->updateAttribute(
                    $entityType,
                    $attributeCode,
                    'backend_model',
                    DateRange::class
                );
            }
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies(): array
    {
        return [
            AddMagenestDateAttributes::class
        ];
    }

    public function getAliases(): array
    {
        return [];
    }
}
