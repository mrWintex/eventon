$(document).ready(function () {
  busy = false;
  all_posts = false;
  offset = 50;
  $(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() >= $(document).height() - offset && !busy && !all_posts){
      LoadMorePosts();
    }
  });

  $("#post-filter").on("change", function(){
    $("#posts-container").empty();
    LoadMorePosts();
  });

  //První načtení příspěvků
  LoadMorePosts();

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
          $("#posts-container").append("<div class='all-posts-alert'><span>Nic dalšího tu není!<button onclick='ScrollToTop()'><i class='fa-solid fa-arrow-up'></i></a></span></button></div>");
          all_posts = true;
        }
        
        $("#posts-container").append(response);
        $("#loader-image").css("visibility", "hidden");
        busy = false;
      }
    });
  }

  function GetFilter(){
    return $("#post-filter").val();
  }
});


