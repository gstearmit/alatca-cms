/**
 * Select2 Vietnamese translation.
 * 
 * Author: tienlx <lexuantien0311@gmail.com>
 */
(function ($) {
    "use strict";

    $.extend($.fn.select2.defaults, {
        formatNoMatches: function () { return "Không có kết quả phù hợp"; },
        formatInputTooShort: function (input, min) { var n = min - input.length; return "Xin hãy nhập nhiều hơn " + n + " kí tự" + (n == 1 ? "" : ""); },
        formatInputTooLong: function (input, max) { var n = input.length - max; return "Xin hãy nhập ít hơn " + n + " kí tự" + (n == 1? "" : ""); },
        formatSelectionTooBig: function (limit) { return "Bạn chỉ có thể chọn " + limit + " bản ghi" + (limit == 1 ? "" : ""); },
        formatLoadMore: function (pageNumber) { return "Đang tải thêm kết quả..."; },
        formatSearching: function () { return "Tìm kiếm..."; }
    });
})(jQuery);
