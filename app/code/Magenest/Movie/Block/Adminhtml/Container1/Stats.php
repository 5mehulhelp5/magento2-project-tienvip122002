<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\Container1;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Module\ModuleListInterface;

class Stats extends Template
{
    public function __construct(
        Context $context,
        private readonly ModuleListInterface $moduleList,
        private readonly ComponentRegistrarInterface $componentRegistrar,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /** Tổng số module đang enable trong Magento */
    public function getTotalModules(): int
    {
        return count($this->moduleList->getAll());
    }

    /** (Cũ) Đếm module không bắt đầu Magento_ (không chuẩn để gọi là core/non-core) */
    public function getNonMagentoModules(): int
    {
        $all = array_keys($this->moduleList->getAll());

        $nonMagento = array_filter($all, static function (string $moduleName): bool {
            return strncmp($moduleName, 'Magento_', 8) !== 0;
        });

        return count($nonMagento);
    }

    /** Đếm module core Magento theo composer package name (magento/*) */
    public function getEnabledMagentoCoreModulesCount(): int
    {
        $enabled = array_keys($this->moduleList->getAll());
        $modulePaths = $this->componentRegistrar->getPaths(ComponentRegistrar::MODULE);

        $count = 0;

        foreach ($enabled as $moduleName) {
            if (!isset($modulePaths[$moduleName])) {
                continue;
            }

            $composerJson = rtrim($modulePaths[$moduleName], DIRECTORY_SEPARATOR) . '/composer.json';
            if (!is_file($composerJson)) {
                continue;
            }

            $data = json_decode((string) file_get_contents($composerJson), true);
            $packageName = $data['name'] ?? '';

            if (is_string($packageName) && strncmp($packageName, 'magento/', 8) === 0) {
                $count++;
            }
        }

        return $count;
    }
}
