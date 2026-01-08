<?php
declare(strict_types=1);

namespace Magenest\CustomWidget\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Hello extends Template implements BlockInterface
{
    protected $_template = 'widget/hello.phtml';

    public function getTitle(): string
    {
        return (string)($this->getData('title') ?? '');
    }

    public function getMessage(): string
    {
        return (string)($this->getData('message') ?? '');
    }
}
