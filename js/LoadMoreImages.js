$(document).ready(function () {
  //===PROMĚNNÉ===
  busy = false;
  all_posts = false;
  offset = 50;
  item_selected = false;

  $load_image = $("#loader-image");
  $post_container = $("#posts-container");
  //==============

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
    data: getData(),
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