<?php
declare(strict_types=1);


namespace Magenest\Movie\Model\Config\Frontend;


use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;


class RedField extends Field
{
    private string $suffix = '_abcd';


    public function render(AbstractElement $element): string
    {
        // Giữ nguyên cấu trúc row chuẩn của Magento (label + Yes/No + scope...)
        // còn 1 cách khác là dùng js nhưng nó thường bị chặn bởi CSP
        $html = parent::render($element);
        $label = (string) $element->getLabel();
        if (!str_ends_with($label, $this->suffix)) {
            return $html;
        }
        $head = substr($label, 0, -strlen($this->suffix));

        $labelEsc = $this->escapeHtml($label);

        $newLabel =
            $this->escapeHtml($head) .
            '<span style="color:#d00;font-weight:600;">' .
            $this->escapeHtml($this->suffix) .
            '</span>';


        return str_replace($labelEsc, $newLabel, $html);
    }
}
