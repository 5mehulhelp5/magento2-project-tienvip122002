# Debug Instructions

## Bước 1: Kiểm tra component type của date fields trong browser

1. Mở product edit form trong admin
2. Mở Developer Console (F12)
3. Chạy lệnh sau:

```javascript
// Kiểm tra tất cả UI components
require('uiRegistry').get(function(component) {
    if (component.dataScope && (component.dataScope.includes('magenest_from_date') || component.dataScope.includes('magenest_to_date'))) {
        console.log('Found component:', component.name);
        console.log('DataScope:', component.dataScope);
        console.log('Component type:', component.component);
        console.log('Full component:', component);
    }
});
```

## Bước 2: Kiểm tra DOM element

```javascript
// Tìm input field trong DOM
var fromDateInput = document.querySelector('[name="product[magenest_from_date]"]');
var toDateInput = document.querySelector('[name="product[magenest_to_date]"]');
console.log('From date input:', fromDateInput);
console.log('To date input:', toDateInput);
```

## Bước 3: Kiểm tra xem mixin có được load không

```javascript
// Xem tất cả các mixins đã load
require.s.contexts._.config.config.mixins
```

## Những gì cần chú ý:

1. **Component type**: Phải là `Magento_Ui/js/form/element/date` thì mixin mới hoạt động
2. **DataScope**: Có thể là `magenest_from_date` hoặc `product[magenest_from_date]`
3. **Element ID/Name**: Để biết selector đúng

## Nếu component type KHÔNG phải là Magento_Ui/js/form/element/date:

Bạn cần tìm đúng component type và update requirejs-config.js để target đúng component đó.

Ví dụ, nếu là `Magento_Catalog/js/components/... ` thì cần mixin cho component đó.
