function showIpLocation() {
    $(".robotx-location").text("正在查询...");
    $(".robotx-ip").each(function() {
        var myd = $(this);
        $.ajax({
            url: "https://ip.huomao.com/ip?ip=" + myd.text(),
            type: 'get',
            dataType: 'json',
            success: function(str) {
                data = eval(str);
                myd.next().text(data.country + data.province + data.city + data.isp).css("color", "#BD6800");
            },
            error: function(e) {
                myd.next().text("无该 IP 详细信息").css("color", "#f00");
            }
        });
    });
}
$(document).ready(function() {
    $(".check-ip-location").click(showIpLocation);
    $(".robotx-ip").click(function() {
        $('.search-ip').val($(this).data('ip'));
        $('.search-btn').trigger('click');
    });
    $(".robotx-bot-name").click(function() {
        $('.search-bot').val($(this).data('bot'));
        $('.search-btn').trigger('click');
    });
    $(".clear-search-ip").click(function() {
        $('.search-ip').val("");
        $('.search-btn').trigger('click');
    });
});