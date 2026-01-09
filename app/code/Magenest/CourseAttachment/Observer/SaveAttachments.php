<?php
namespace Magenest\CourseAttachment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magenest\CourseAttachment\Model\AttachmentFactory;
use Magenest\CourseAttachment\Model\ResourceModel\Attachment\CollectionFactory;

class SaveAttachments implements ObserverInterface
{
    // Tên key chứa dữ liệu gửi lên (phải khớp với Modifier)
    const DATA_SCOPE = 'magenest_course_attachments';

    protected $request;
    protected $attachmentFactory;
    protected $collectionFactory;

    public function __construct(
        RequestInterface $request,
        AttachmentFactory $attachmentFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->request = $request;
        $this->attachmentFactory = $attachmentFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute(Observer $observer)
    {
        // 1. Lấy Product ID vừa lưu
        $product = $observer->getEvent()->getProduct();
        $productId = $product->getId();

        // 2. Lấy dữ liệu từ Form gửi lên
        // Dữ liệu nằm trong mảng: product[magenest_course_attachments]
        $formData = $this->request->getPostValue();
        
        // Nếu không có dữ liệu attachment thì dừng (hoặc xử lý xóa hết nếu cần)
        if (!isset($formData['product'][self::DATA_SCOPE])) {
            return;
        }

        $incomingData = $formData['product'][self::DATA_SCOPE];

        // 3. XỬ LÝ LOGIC: XÓA - THÊM - SỬA

        // BƯỚC 3A: Xóa các dòng đã bị Admin xóa trên UI
        // Lấy danh sách ID đang có trong DB
        $existingCollection = $this->collectionFactory->create();
        $existingCollection->addFieldToFilter('product_id', $productId);
        
        $existingIds = [];
        foreach ($existingCollection as $item) {
            $existingIds[] = $item->getId();
        }

        // Lấy danh sách ID từ Form gửi lên (những cái còn giữ lại)
        $incomingIds = [];
        foreach ($incomingData as $row) {
            if (isset($row['entity_id']) && !empty($row['entity_id'])) {
                $incomingIds[] = $row['entity_id'];
            }
        }

        // Tìm những ID có trong DB mà không có trong Form -> Xóa đi
        $idsToDelete = array_diff($existingIds, $incomingIds);
        if (!empty($idsToDelete)) {
            $this->deleteAttachments($idsToDelete);
        }

        // BƯỚC 3B: Lưu hoặc Cập nhật dữ liệu mới
        foreach ($incomingData as $row) {
            // Bỏ qua dòng template rỗng (nếu có)
            if (isset($row['delete']) && $row['delete'] == 1) {
                continue; 
            }
            
            // Khởi tạo model
            $model = $this->attachmentFactory->create();

            // Nếu có ID -> Load cũ để Update
            if (isset($row['entity_id']) && !empty($row['entity_id'])) {
                $model->load($row['entity_id']);
            }

            // Gán dữ liệu
            $model->setData('product_id', $productId); // Luôn set lại Product ID cho chắc
            $model->setData('label', $row['label']);
            $model->setData('file_type', $row['file_type']);
            $model->setData('file_path', $row['file_path']);
            $model->setData('sort_order', (int)$row['sort_order']);

            // Lưu vào DB
            try {
                $model->save();
            } catch (\Exception $e) {
                // Ghi log lỗi nếu cần
            }
        }
    }

    /**
     * Hàm phụ để xóa nhiều dòng cùng lúc
     */
    private function deleteAttachments($ids)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('entity_id', ['in' => $ids]);
        foreach ($collection as $item) {
            $item->delete();
        }
    }
}