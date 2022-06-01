$(document).ready(function () {
  //===PROMĚNNÉ===
  busy = false;
  all_posts = false;
  offset = 50;

  $post_container = $("#posts-container");
  $post_filter = $("#posts-selector-filter-input");
  $post_search = $("#post-search");
  $search_list = $(".posts-selector-search-results-list");
  $load_image = $("#loader-image");
  $post_search_data = $("#posts-selector-search-select");
  item_selected = false;
  //==============

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

  //profile page záložky
  $(".profile-data-select-item").on("click", function () {
    if($(this).hasClass("active")) return;
    $(".profile-data-select-item").removeClass("active");
    $(this).addClass("active");
    all_posts = false;
    ScrollToTop(1);
    LoadMorePosts(true);
  });

  $(window).scroll(function () {
    //pokud je stránka úplně dole načtou se další příspěvky
    if ($(window).scrollTop() + $(window).height() >= $(document).height() - offset && !all_posts) {
      LoadMorePosts();
    }
  });

  //První načtení příspěvků
  LoadMorePosts();
});

//Funkce pro načtení příspěvků pomocí AJAX
function LoadMorePosts(clear = false) {
  if (busy === true) return;
  busy = true;
  $.ajax({
    type: "POST",
    url: "./php/postmanager.php",
    data: {
      filter: GetFilter(),
      search_value: GetSearchValue(),
      search_data: GetSearchData(),
      showcontrols: $(".profile-data-select-item.active").attr("data-id") == 0,
    },
    beforeSend: function () {
      $load_image.css("visibility", "visible");
    },
    success: function (response) {
      if (!response || response === " ") {
        $post_container.append("<div class='all-posts-alert'><span class='alert-text'>Nic dalšího tu není!<button class='alert-button' onclick='ScrollToTop()'><i class='fa-solid fa-arrow-up fa-bounce' style=' --fa-bounce-start-scale-x: 1; --fa-bounce-start-scale-y: 1; --fa-bounce-jump-scale-x: 1; --fa-bounce-jump-scale-y: 1; --fa-bounce-land-scale-x: 1; --fa-bounce-land-scale-y: 1; --fa-bounce-rebound: 0; --fa-bounce-height: -5px;'></i></a></span></button></div>");
        all_posts = true;
      }
      clear ? $post_container.html(response) : $post_container.append(response);
      $load_image.css("visibility", "hidden");
      busy = false;
    }
  });
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

function TagSearch(tag) {
  item_selected = true;
  all_posts = false;

  $post_search.val($(tag).text());
  $post_search.attr("data-id", $(tag).attr("data-id"));
  $post_search_data.val(1);

  ScrollToTop(1);
  LoadMorePosts(true);
}
