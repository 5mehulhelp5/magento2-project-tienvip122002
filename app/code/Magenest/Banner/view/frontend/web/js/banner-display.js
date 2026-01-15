define([
    'uiComponent',
    'ko',
    'jquery',
    'mage/url'
], function (Component, ko, $, urlBuilder) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magenest_Banner/banner-list', // File HTML template (bước 4)
            banners: ko.observableArray([]) // Biến chứa danh sách banner
        },

        initialize: function () {
            this._super();
            this.getBannerData();
        },

        getBannerData: function () {
            var self = this;
            // Gọi AJAX lên Controller bước 2
            var serviceUrl = urlBuilder.build('magenest_banner/index/json');

            $.ajax({
                url: serviceUrl,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    console.log("Banner Data:", data); // Log để debug
                    self.banners(data); // Đẩy dữ liệu vào biến observable
                }
            });
        }
    });
});