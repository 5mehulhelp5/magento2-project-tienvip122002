define([
    'Magento_Ui/js/form/element/date',
    'jquery'
], function (DateElement, $) {
    'use strict';

    return DateElement.extend({
        defaults: {
            options: {
                showsTime: true,
                beforeShowDay: function (date) {
                    var day = date.getDate();
                    // Allow only 8th to 12th
                    if (day >= 8 && day <= 12) {
                        return [true, ''];
                    }
                    return [false, '', 'Only days 8-12 are allowed'];
                }
            }
        }
    });
});
