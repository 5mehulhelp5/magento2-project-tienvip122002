<?php
namespace Magenest\SourceTime\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

class DurationTable extends AbstractFieldArray
{
    private $groupRenderer;

    protected function _prepareToRender()
    {
        // Cột 1: Chọn Group (Dropdown)
        $this->addColumn('customer_group_id', [
            'label' => __('Customer Group'),
            'renderer' => $this->getGroupRenderer()
        ]);

        // Cột 2: Nhập số ngày (Input)
        $this->addColumn('duration_days', [
            'label' => __('Access Duration (Days)'),
            'style' => 'width:100px',
            'class' => 'validate-number required-entry' // Validate bắt buộc là số
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Policy');
    }

    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];
        $groupRenderer = $this->getGroupRenderer();
        
        // Logic để khi load lại trang nó tự chọn đúng cái Dropdown cũ
        if ($row->getCustomerGroupId()) {
            $optionHash = $groupRenderer->calcOptionHash($row->getCustomerGroupId());
            $options['option_' . $optionHash] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    private function getGroupRenderer()
    {
        if (!$this->groupRenderer) {
            $this->groupRenderer = $this->getLayout()->createBlock(
                \Magenest\SourceTime\Block\Adminhtml\System\Config\Field\CustomerGroupColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->groupRenderer;
    }
}