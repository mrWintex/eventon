$.getScript("./js/LoadMoreImages.js");

$(function ($) {
    getData = () => {
        return {
            filter: $(".profile-data-select-item.active").attr("data-id"),
            search_value: -1,
            search_data: 2,
            showcontrols: $(".profile-data-select-item.active").attr("data-id") == 0
        };
    };
    //profile page záložky
    $(".profile-data-select-item").on("click", function () {
        if ($(this).hasClass("active")) return;
        $(".profile-data-select-item").removeClass("active");
        $(this).addClass("active");
        all_posts = false;
        ScrollToTop(1);
        LoadMorePosts(true);
    });
});