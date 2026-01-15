<?php
namespace Magenest\Banner\Block\Adminhtml\Banner\Edit;

use Magento\Backend\Block\Widget\Context;
use Magenest\Banner\Model\BannerFactory;

class GenericButton
{
    protected $context;
    protected $bannerFactory;

    public function __construct(
        Context $context,
        BannerFactory $bannerFactory
    ) {
        $this->context = $context;
        $this->bannerFactory = $bannerFactory;
    }

    public function getBannerId()
    {
        try {
            return $this->context->getRequest()->getParam('banner_id');
        } catch (\Exception $e) {
        }
        return null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}