function showIpLocation(event, ip) {
    $(event).text("正在查询...");
    $.ajax({
        url: "https://api.ip.sb/geoip/" + ip,
        type: 'get',
        dataType: 'json',
        success: function (str) {
            $(event).text("国家:" + str.country + "\n城市:" + str.city + "\n运营商:" + str.isp).css("color", "#BD6800");
        },
        error: function (e) {
            console.log("ip查询错误", e)
            $(event).text("查询错误").css("color", "#f00");
        }
    });
}

$(document).ready(function () {
    $(".robotx-ip").click(function () {
        $('.search-ip').val($(this).data('ip'));
        $('.search-btn').trigger('click');
    });
    $(".robotx-bot-name").click(function () {
        $('.search-bot').val($(this).data('bot'));
        $('.search-btn').trigger('click');
    });
    $(".clear-search-ip").click(function () {
        $('.search-ip').val("");
        $('.search-btn').trigger('click');
    });
});