<?php
namespace Magenest\Banner\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;
use Magenest\Banner\Model\BannerFactory;

class Delete extends Action
{
    protected $bannerFactory;

    public function __construct(
        Action\Context $context,
        BannerFactory $bannerFactory
    ) {
        $this->bannerFactory = $bannerFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        // 1. Lấy ID từ URL
        $id = $this->getRequest()->getParam('banner_id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                // 2. Init Model
                $model = $this->bannerFactory->create();
                $model->load($id);

                // 3. Xóa
                $model->delete();

                // 4. Thông báo thành công
                $this->messageManager->addSuccessMessage(__('The banner has been deleted.'));
                
                // Quay về trang Grid
                return $resultRedirect->setPath('*/*/');

            } catch (\Exception $e) {
                // 5. Xử lý lỗi
                $this->messageManager->addErrorMessage($e->getMessage());
                // Nếu lỗi thì quay lại trang Edit của banner đó
                return $resultRedirect->setPath('*/*/edit', ['banner_id' => $id]);
            }
        }

        // 6. Nếu không tìm thấy ID
        $this->messageManager->addErrorMessage(__('We can\'t find a banner to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}