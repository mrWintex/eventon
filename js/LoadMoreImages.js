$(document).ready(function () {
  //===PROMĚNNÉ===
  //bool zda se již ajax request zpracovává
  busy = false;
  //bool zda již všechny příspěvky byly zobrazeny
  all_posts = false;
  //odsazení prahu zobrazování příspěvků
  offset = 50;
  //==============


  $(window).scroll(function() {
    //pokud je stránka úplně dole načtou se další příspěvky
    if($(window).scrollTop() + $(window).height() >= $(document).height() - offset && !busy && !all_posts){
      LoadMorePosts();
    }
  });

  //Pokud byl změněn filtr, obnoví se příspěvky
  $("#post-filter").on("change", function(){
    $("#posts-container").empty();
    LoadMorePosts();
  });

  //První načtení příspěvků
  LoadMorePosts();

  //Funkce pro načtení příspěvků pomocí AJAX
  function LoadMorePosts(){
    busy = true;
    $.ajax({
      type: "GET",
      url: "./php/postmanager.php?filter="+GetFilter(),
      beforeSend: function(){
        $("#loader-image").css("visibility", "visible");
      },
      success: function (response) {
        if(!response || response === " "){
          $("#posts-container").append("<div class='all-posts-alert'><span class='alert-text'>Nic dalšího tu není!<button class='alert-button' onclick='ScrollToTop()'><i class='fa-solid fa-arrow-up fa-bounce' style=' --fa-bounce-start-scale-x: 1; --fa-bounce-start-scale-y: 1; --fa-bounce-jump-scale-x: 1; --fa-bounce-jump-scale-y: 1; --fa-bounce-land-scale-x: 1; --fa-bounce-land-scale-y: 1; --fa-bounce-rebound: 0;'></i></a></span></button></div>");
          all_posts = true;
        }
        
        $("#posts-container").append(response);
        $("#loader-image").css("visibility", "hidden");
        busy = false;
      }
    });
  }

  //Získání id filtru pro SQL dotaz
  function GetFilter(){
    return $("#post-filter").val();
  }
});


