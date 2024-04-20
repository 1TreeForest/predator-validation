function update_checker(force = false, before, complete) {
    let force_update = force === true ? "&force=true" : "";

    $.ajax({
        url: BASEDIR + "administration/includes/?api=update-checker" + force_update,
        method: "get",
        dataType: "json",
        beforeSend: before,
        success: function (e) {
            $("#updatechecker_result").html(e.result).show();
        },
        complete: complete
    });
}

function execute_ajax(step, before, complete) {
    $.ajax({
        url: BASEDIR + "administration/includes/?api=update-core&step=" + step,
        method: "get",
        dataType: "json",
        beforeSend: before,
        success: function (e) {
            $("#update-results").append(e.result);
        },
        complete: complete
    });
}

$(function () {
    $("#forceupdate").on("click", function (e) {
        e.preventDefault();
        update_checker(true, function () {
            $("#forceupdate > i").show();
        }, function () {
            $("#forceupdate > i").hide();
        });
    });

    $("#updatelocales").on("click", function (e) {
        e.preventDefault();
        execute_ajax('update_langs', function () {
            $("#update-results").append(locale['U_013'] + '<br>');
            $("#updatelocales > i").show();
        }, function () {
            $("#updatelocales > i").hide();
        });
    });

    $("#updatecore").on("click", function (e) {
        e.preventDefault();
        $("#new_update_box").hide();
        execute_ajax('update_core', function () {
            $("#update-results").append('<div id="update_icon"><i class="fas fa-spinner fa-pulse fa-3x"></i></div>');
        }, function () {
            $("#update-results #update_icon").hide();
        });
    });
});
