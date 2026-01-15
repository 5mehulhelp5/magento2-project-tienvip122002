<?php
namespace Magenest\Banner\Controller\Adminhtml\Banner; // Namespace phải đúng Adminhtml

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Model\ImageUploader;

class Upload extends Action // Tên Class trùng tên File
{
    protected $imageUploader;

    public function __construct(
        Context $context,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    // Quan trọng: Phải check quyền ACL, nếu không có hàm này admin có thể chặn
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Banner::banner');
    }

    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('image');
            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
        } catch (\Exception $e) {
            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData([
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode()
            ]);
        }
    }
}