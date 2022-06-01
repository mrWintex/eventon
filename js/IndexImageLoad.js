getData = () => {
    return {
        filter: $post_filter.val(),
        search_value: $post_search.attr("data-id"),
        search_data: $post_search_data.val(),
        showcontrols: false
    };
};
$.getScript("./js/LoadMoreImages.js");

$(function ($) {
    $post_filter = $("#posts-selector-filter-input");
    $post_search = $("#post-search");
    $search_list = $(".posts-selector-search-results-list");
    $post_search_data = $("#posts-selector-search-select");


    //Pokud byl změněn filtr, obnoví se příspěvky
    $post_filter.on("change", function () {
        all_posts = false;
        LoadMorePosts(true);
    });

    //Pokud byla zadána hodnota do vyhledavače, zobrazí se výsledky
    $post_search.on("keyup", function () {
        ReloadSearchResults($(this).val());
    }).on("focusin", function () {
        $search_list.css("display", "block");
    }).on("focusout", function () {
        setTimeout(function () {
            $search_list.css("display", "none");
        }, 100);
    });

    //při zmáčknutí entru nebo při odchodu z vyhledávače se zkontroluje zda je vymazaný, a zruší se veškeré filtry
    $post_search.on("change", function () {
        if ($(this).val() == "" && item_selected === true) {
            all_posts = false;
            item_selected = false;
            $post_search.attr("data-id", -1);
            LoadMorePosts(true);
        }
    });
});

//Vyhledání tagu po kliknutí na něj u příspevku
function TagSearch(tag) {
    item_selected = true;
    all_posts = false;

    $post_search.val($(tag).text());
    $post_search.attr("data-id", $(tag).attr("data-id"));
    $post_search_data.val(1);

    ScrollToTop(1);
    LoadMorePosts(true);
}

//Funkce pro načtení výsledku vyhledávače
function ReloadSearchResults(search_value) {
    $.ajax({
        type: "GET",
        url: "./php/post_search_manager.php?search_value=" + search_value + "&search_data=" + GetSearchData(),
        beforeSend: () => $("#loader-image-post-selector").css("display", "block"),
        success: function (r) {
            $("#loader-image-post-selector").css("display", "none");
            $search_list.html(r);
            $(".posts-selector-search-results-list-item").on("mousedown", function () {
                all_posts = false;
                item_selected = true;

                $post_search.val($(this).attr("data-usrnm"));
                $post_search.attr("data-id", $(this).attr("data-id"));
                ReloadSearchResults($(this).attr("data-usrnm"));
                LoadMorePosts(true);
            });
        }
    });
}

//získání filtrovacích dat
function GetFilter() {
    if (window.location.href.indexOf("profile.php") >= 0) return $(".profile-data-select-item.active").attr("data-id");
    return $post_filter.val();
}
function GetSearchData() {
    if (window.location.href.indexOf("profile.php") >= 0) return 2;
    return $post_search_data.val();
}
function GetSearchValue() {
    if (window.location.href.indexOf("profile.php") >= 0) return -1;
    return $post_search.attr("data-id");
}