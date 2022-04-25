$(document).ready(function () {
    $(document).click(function (e) { 
        if($(e.target).parents(".profile")[0] || $(e.target).hasClass("profile"))return;
        $(".user-options").removeClass("user-options-show");
    });

    $(".profile").click(function (e) { 
        $(this).find(".user-options").toggleClass("user-options-show");
    });
});