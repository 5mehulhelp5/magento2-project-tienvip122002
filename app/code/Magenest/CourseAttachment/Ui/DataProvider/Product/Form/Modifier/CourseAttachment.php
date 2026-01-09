<?php
namespace Magenest\CourseAttachment\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Field;
use Magento\Framework\Stdlib\ArrayManager;
use Magenest\CourseAttachment\Model\ResourceModel\Attachment\CollectionFactory;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Eav\Api\AttributeSetRepositoryInterface; // <-- Thêm thư viện này

class CourseAttachment extends AbstractModifier
{
    const DATA_SCOPE = 'magenest_course_attachments';
    const GROUP_NAME = 'magenest_course_attachment_group';
    const TARGET_ATTRIBUTE_SET = 'Course'; // Tên Attribute Set muốn áp dụng

    protected $arrayManager;
    protected $collectionFactory;
    protected $locator;
    protected $attributeSetRepository; // <-- Khai báo biến mới

    public function __construct(
        ArrayManager $arrayManager,
        CollectionFactory $collectionFactory,
        LocatorInterface $locator,
        AttributeSetRepositoryInterface $attributeSetRepository // <-- Inject vào đây
    ) {
        $this->arrayManager = $arrayManager;
        $this->collectionFactory = $collectionFactory;
        $this->locator = $locator;
        $this->attributeSetRepository = $attributeSetRepository;
    }

    public function modifyData(array $data)
    {
        // 1. Kiểm tra Attribute Set trước khi load dữ liệu
        if (!$this->isCourseAttributeSet()) {
            return $data;
        }

        $product = $this->locator->getProduct();
        $productId = $product->getId();

        if ($productId) {
            $collection = $this->collectionFactory->create()
                ->addFieldToFilter('product_id', $productId)
                ->setOrder('sort_order', 'ASC');

            $items = [];
            foreach ($collection as $item) {
                $items[] = $item->getData();
            }

            $data[$productId]['product'][static::DATA_SCOPE] = $items;
        }

        return $data;
    }

    public function modifyMeta(array $meta)
    {
        // 2. Kiểm tra Attribute Set trước khi vẽ UI
        if (!$this->isCourseAttributeSet()) {
            return $meta; // Nếu không phải 'Course' thì trả về nguyên trạng, không thêm bảng
        }

        $meta = $this->arrayManager->set(
            self::GROUP_NAME,
            $meta,
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Course Attachments'),
                            'componentType' => 'fieldset',
                            'dataScope' => 'data.product',
                            'collapsible' => true,
                            'sortOrder' => 20,
                        ],
                    ],
                ],
                'children' => [
                    static::DATA_SCOPE => $this->getDynamicRowsConfig()
                ],
            ]
        );

        return $meta;
    }

    /**
     * Hàm helper: Check xem sản phẩm hiện tại có phải thuộc Set 'Course' không
     */
    protected function isCourseAttributeSet()
    {
        try {
            $product = $this->locator->getProduct();
            $setId = $product->getAttributeSetId();

            if (!$setId) {
                return false;
            }

            // Lấy thông tin Attribute Set từ ID
            $attributeSet = $this->attributeSetRepository->get($setId);

            // So sánh tên (Hoặc bạn có thể so sánh ID nếu muốn cứng nhắc hơn)
            return $attributeSet->getAttributeSetName() === self::TARGET_ATTRIBUTE_SET;

        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getDynamicRowsConfig()
    {
        // (Giữ nguyên hàm này như phiên bản đã sửa 'dataScope' => static::DATA_SCOPE)
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'dynamicRows',
                        'label' => __('Attachments'),
                        'renderDefaultRecord' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => static::DATA_SCOPE, // Đã sửa đúng ở bước trước
                        'dndConfig' => ['enabled' => true],
                        'addButton' => true,
                        'itemTemplate' => 'record',
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => 'container',
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => [
                        'entity_id' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => '',
                                        'dataScope' => 'entity_id',
                                        'visible' => false,
                                    ],
                                ],
                            ],
                        ],
                        'label' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Label'),
                                        'dataScope' => 'label',
                                        'sortOrder' => 10,
                                        'validation' => ['required-entry' => true],
                                    ],
                                ],
                            ],
                        ],
                        'file_type' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Select::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Type'),
                                        'dataScope' => 'file_type',
                                        'options' => [
                                            ['value' => 'file', 'label' => __('File Upload')],
                                            ['value' => 'link', 'label' => __('External Link')],
                                        ],
                                        'sortOrder' => 20,
                                    ],
                                ],
                            ],
                        ],
                        'file_path' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('URL / File Path'),
                                        'dataScope' => 'file_path',
                                        'sortOrder' => 30,
                                    ],
                                ],
                            ],
                        ],
                        'sort_order' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => 'number',
                                        'label' => __('Order'),
                                        'dataScope' => 'sort_order',
                                        'sortOrder' => 40,
                                    ],
                                ],
                            ],
                        ],
                        'actionDelete' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => 'actionDelete',
                                        'dataType' => Text::NAME,
                                        'label' => '',
                                        'sortOrder' => 50,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}