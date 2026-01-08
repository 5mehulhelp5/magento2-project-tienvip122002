# Date Restriction Troubleshooting

## Problem
The date picker was not disabling days outside of 8-12 range.

## Root Causes

### 1. Missing Date Fields in UI Component
The `product_form.xml` file did not contain the actual date field components that the mixin was trying to target:
- Missing: `magenest_from_date` field
- Missing: `magenest_to_date` field

The mixin was looking for these fields by `dataScope`, but they simply didn't exist in the DOM.

### 2. Incorrect Calendar API Usage
The original mixin was trying to use jQuery UI datepicker API:
```javascript
$el.datepicker('option', 'beforeShowDay', function (date) { ... });
```

However, Magento 2 uses its own calendar component which doesn't use jQuery UI datepicker. The calendar options need to be configured through the UI component's `options.options` property.

### 3. Timing Issue
The original approach tried to apply the rule in `initDatepicker()` by manipulating the DOM element directly. This approach has timing issues because:
- The component may not be fully rendered
- jQuery UI datepicker may not be initialized
- Magento uses a different calendar implementation

## Solution

### Step 1: Add Date Fields to UI Component
Added the date fields to `view/adminhtml/ui_component/product_form.xml`:

```xml
<field name="magenest_from_date" formElement="date">
    <argument name="data" xsi:type="array">
        <item name="config" xsi:type="array">
            <item name="label" xsi:type="string" translate="true">From Date (Days 8-12 only)</item>
            <item name="dataScope" xsi:type="string">magenest_from_date</item>
            <item name="sortOrder" xsi:type="number">10</item>
        </item>
    </argument>
    <settings>
        <validation>
            <rule name="validate-date" xsi:type="boolean">true</rule>
        </validation>
        <dataType>string</dataType>
    </settings>
</field>
```

### Step 2: Fix the Mixin to Use Magento Calendar API
Updated `web/js/date-only-8-12-mixin.js` to configure the calendar options properly:

```javascript
initConfig: function () {
    this._super();
    
    // Only apply to specific fields
    if (this.dataScope !== 'magenest_from_date' && this.dataScope !== 'magenest_to_date') {
        return this;
    }
    
    // Configure calendar options through Magento's UI component API
    if (!this.options) {
        this.options = {};
    }
    if (!this.options.options) {
        this.options.options = {};
    }
    
    this.options.options.beforeShowDay = function (date) {
        var day = date.getDate();
        var isAllowed = (day >= 8 && day <= 12);
        return [isAllowed, isAllowed ? '' : 'disabled', isAllowed ? '' : 'Only days 8-12 are allowed'];
    };
    
    return this;
}
```

Key changes:
- Use `initConfig()` instead of `initDatepicker()` - this runs earlier in the component lifecycle
- Configure through `this.options.options` instead of manipulating DOM directly
- This approach works with Magento's calendar component implementation

### Step 3: Clear Cache and Deploy
```bash
php bin/magento cache:flush
php bin/magento setup:static-content:deploy -f
```

## How to Test
1. Go to Catalog > Products > Edit any product
2. Scroll to "Magenest First Section"
3. Click on either "From Date" or "To Date" field
4. The calendar should show only days 8-12 as clickable
5. Other days should be disabled and show tooltip "Only days 8-12 are allowed"

## References
- Magento UI Component Calendar: `vendor/magento/module-ui/view/base/web/js/form/element/date.js`
- RequireJS Mixin: https://developer.adobe.com/commerce/frontend-core/javascript/mixins/
