$(document).ready(function () {
    //Pokud uživatel klikne na navigaci zobrazí se
    $(".profile").click(function (e) { 
        $(this).find(".user-options").toggleClass("user-options-show");
    });

    //Pokud uživatel klikne mimo navigaci schová se
    $(document).click(function (e) { 
        if($(e.target).parents(".profile")[0] || $(e.target).hasClass("profile"))return;
        $(".user-options").removeClass("user-options-show");
    });
});