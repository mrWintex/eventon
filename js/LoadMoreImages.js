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
  //==============



  $(window).scroll(function() {
    //pokud je stránka úplně dole načtou se další příspěvky
    if($(window).scrollTop() + $(window).height() >= $(document).height() - offset && !all_posts){
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
    ReloadSearchResults($(this).val());
  }).on("focusin", function(){
    $search_list.css("display", "block");
  }).on("focusout", function(){
    setTimeout(function(){
      $search_list.css("display", "none");
    }, 100);
  });

  $post_search.on("change", function(){
    if($(this).val() == ""){
      all_posts = false;
      $post_search.attr("data-id", -1);
      $post_container.empty();
      LoadMorePosts();
    }
  });

  //První načtení příspěvků
  LoadMorePosts();

  //Funkce pro načtení příspěvků pomocí AJAX
  function LoadMorePosts(){
    if(busy === true) return;
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
    $.ajax({
      type: "GET",
      url: "./php/post_search_manager.php?search_value="+ search_value + "&search_data="+GetSearchData(),
      success: function (r) {  
        $search_list.html(r);
        $(".posts-selector-search-results-list-item").on("mousedown", function(){
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


