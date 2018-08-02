/**
 * ajax分页函数
 * @param find    根据页码查询相应记录的页面的url
 * @param maxpage   最大页数
 * @param listbox    显示当前页记录的盒子的类名
 * @param prev       上一页a链接的类名
 * @param next       下一页a链接的类名
 * @param pagenum    页码a链接的类名
 * @param now        当前显示记录的页码的样式的类名，这个不用加.
 */

function ajaxPage(find,maxpage,listbox,prev,next,pagenum,now) {
    var page = 1;

    $(pagenum).eq(0).addClass(now);
    $.ajax({
        url: find,
        type: "get",
        data: {p: page},
        success: function (data) {
            $(listbox).html(data);
        }
    });

    $(prev).click(function () {
        page--;
        if (page <= 0) {
            page = 1
        }
        $.ajax({
            url: find,
            type: "get",
            data: {p: page},
            success: function (data) {
                $(listbox).html(data);
            }
        });

        inde = page - 1;
        $(pagenum).eq(inde).addClass(now).siblings(pagenum).removeClass(now);
    });

    $(next).click(function () {
        page++;
        if (page >= Number(maxpage)) {
            page = Number(maxpage);
        }
        $.ajax({
            url: find,
            type: "get",
            data: {p: page},
            success: function (data) {
                $(listbox).html(data);
            }
        });

        inde = page - 1;
        $(pagenum).eq(inde).addClass(now).siblings(pagenum).removeClass(now);
    });

    $(pagenum).click(function () {
        page = $(this).html();
        $.ajax({
            url: find,
            type: "get",
            data: {p: page},
            success: function (data) {
                $(listbox).html(data);
            }
        });

        inde = page - 1;
        $(pagenum).eq(inde).addClass(now).siblings(pagenum).removeClass(now);
    });
}