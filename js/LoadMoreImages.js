$(document).ready(function () {
  //===PROMĚNNÉ===
  //bool zda se již ajax request zpracovává
  busy = false;
  //bool zda již všechny příspěvky byly zobrazeny
  all_posts = false;
  //odsazení prahu zobrazování příspěvků
  offset = 50;
  //==============
  $post_container = $("#posts-container");
  $post_filter = $("#posts-selector-filter-input");
  $post_search = $("#post-search");
  $search_list = $(".posts-selector-search-results-list");
  $search_list_item = $(".posts-selector-search-results-list-item");
  $load_image = $("#loader-image");
  $post_search_data = $("#posts-selector-search-select");



  $(window).scroll(function() {
    //pokud je stránka úplně dole načtou se další příspěvky
    if($(window).scrollTop() + $(window).height() >= $(document).height() - offset && !busy && !all_posts){
      LoadMorePosts();
    }
  });

  //Pokud byl změněn filtr, obnoví se příspěvky
  $post_filter.on("change", function(){
    all_posts = false;
    $post_container.empty();
    LoadMorePosts();
  });

  $post_search.on("keyup", function(){
    if($(this).val() == ""){
      $post_search.attr("data-id", -1);
      $post_container.empty();
      LoadMorePosts();
    }
    ReloadSearchResults($(this).val());
  }).on("focusin", function(){
    $search_list.css("display", "block");
  }).on("focusout", function(){
    setTimeout(function(){
      $search_list.css("display", "none");
    }, 100);
  });

  //První načtení příspěvků
  LoadMorePosts();

  //Funkce pro načtení příspěvků pomocí AJAX
  function LoadMorePosts(){
    busy = true;
    $.ajax({
      type: "GET",
      url: "./php/postmanager.php?filter="+GetFilter()+"&search_value="+GetSearchValue()+"&search_data="+GetSearchData(),
      beforeSend: function(){
        $load_image.css("visibility", "visible");
      },
      success: function (response) {
        if(!response || response === " "){
          $post_container.append("<div class='all-posts-alert'><span class='alert-text'>Nic dalšího tu není!<button class='alert-button' onclick='ScrollToTop()'><i class='fa-solid fa-arrow-up fa-bounce' style=' --fa-bounce-start-scale-x: 1; --fa-bounce-start-scale-y: 1; --fa-bounce-jump-scale-x: 1; --fa-bounce-jump-scale-y: 1; --fa-bounce-land-scale-x: 1; --fa-bounce-land-scale-y: 1; --fa-bounce-rebound: 0; --fa-bounce-height: -5px;'></i></a></span></button></div>");
          all_posts = true;
        }
        $post_container.append(response);
        $load_image.css("visibility", "hidden");
        busy = false;
      }
    });
  }

  function ReloadSearchResults(search_value){
    var search_data_index = $("#posts-selector-search-select").val();
    $.ajax({
      type: "GET",
      url: "./php/post_search_manager.php?search_value="+ search_value + "&search_data="+search_data_index,
      success: function (r) {  
        $search_list.html(r);
        $search_list_item.on("click", function(){
          all_posts = false;
          $post_search.val($(this).attr("data-usrnm"));
          $post_search.attr("data-id", $(this).attr("data-id"));
          $post_container.empty();
          ReloadSearchResults($(this).attr("data-usrnm"));
          LoadMorePosts();
        });
      }
    });
  }

  //Získání id filtru pro SQL dotaz
  function GetFilter(){
    return $post_filter.val();
  }
  function GetSearchData(){
    return $post_search_data.val();
  }
  function GetSearchValue(){
    return $post_search.attr("data-id");
  }
});


