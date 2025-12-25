<?php
declare(strict_types=1);

namespace Magenest\Movie\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ReloadButton extends Field
{
    protected function _getElementHtml(AbstractElement $element): string
    {
        $btnId = $element->getHtmlId() . '_btn';

        return '
            <button type="button" class="action-default" id="' . $btnId . '">
                Reload
            </button>
            <script>
                require(["jquery"], function ($) {
                    $("#' . $btnId . '").on("click", function () {
                        window.location.reload();
                    });
                });
            </script>
        ';
    }
}
