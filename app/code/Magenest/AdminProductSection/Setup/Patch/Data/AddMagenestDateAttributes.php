<?php
declare(strict_types=1);

namespace Magenest\AdminProductSection\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddMagenestDateAttributes implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly EavSetupFactory $eavSetupFactory,
        private readonly EavConfig $eavConfig
    ) {}

    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $entityType = Product::ENTITY;
        $entityTypeId = (int)$this->eavConfig->getEntityType($entityType)->getEntityTypeId();

        $attrs = [
            'magenest_from_date' => 'From Date',
            'magenest_to_date'   => 'To Date',
        ];

        foreach ($attrs as $code => $label) {
            if (!$eavSetup->getAttributeId($entityType, $code)) {
                $eavSetup->addAttribute(
                    $entityType,
                    $code,
                    [
                        'type' => 'datetime',
                        'label' => $label,
                        'input' => 'date',
                        'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\Datetime::class,
                        'required' => false,
                        'visible' => true,
                        'user_defined' => true,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                        'sort_order' => 10,
                        'visible_on_front' => false,
                        'used_in_product_listing' => false,
                    ]
                );
            }
        }

        // Add vào tất cả attribute set + group "Magenest"
        $setTable = $this->moduleDataSetup->getTable('eav_attribute_set');
        $setIds = $this->moduleDataSetup->getConnection()->fetchCol(
            "SELECT attribute_set_id FROM {$setTable} WHERE entity_type_id = ?",
            [$entityTypeId]
        );

        foreach ($setIds as $setId) {
            $setId = (int)$setId;
            $groupName = 'Magenest';
            $groupId = $eavSetup->getAttributeGroupId($entityType, $setId, $groupName);
            if (!$groupId) {
                $eavSetup->addAttributeGroup($entityType, $setId, $groupName, 0);
            }

            foreach (array_keys($attrs) as $code) {
                $eavSetup->addAttributeToSet($entityType, $setId, $groupName, $code);
            }
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies(): array { return []; }
    public function getAliases(): array { return []; }
}
