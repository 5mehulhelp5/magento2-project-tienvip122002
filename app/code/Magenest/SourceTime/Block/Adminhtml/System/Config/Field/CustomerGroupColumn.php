<?php
namespace Magenest\SourceTime\Block\Adminhtml\System\Config\Field;

use Magento\Framework\View\Element\Html\Select;
use Magento\Framework\View\Element\Context;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;

class CustomerGroupColumn extends Select
{
    protected $groupCollectionFactory;

    public function __construct(
        Context $context,
        CollectionFactory $groupCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->groupCollectionFactory = $groupCollectionFactory;
    }

    public function setInputName($value)
    {
        return $this->setName($value);
    }

    public function _toHtml()
    {
        if (!$this->getOptions()) {
            $groups = $this->groupCollectionFactory->create();
            foreach ($groups as $group) {
                $this->addOption($group->getId(), $group->getCustomerGroupCode());
            }
        }
        return parent::_toHtml();
    }
}