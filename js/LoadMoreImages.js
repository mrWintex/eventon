$(document).ready(function () {
  busy = false;
  all_posts = false;
  offset = 50;
  $(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() >= $(document).height() - offset && !busy && !all_posts){
      LoadMorePosts();
    }
  });

  //První načtení příspěvků
  LoadMorePosts();

  function LoadMorePosts(){
    busy = true;
    $.ajax({
      type: "GET",
      url: "./php/postmanager.php?filter=none",
      beforeSend: function(){
        $("#loader-image").css("visibility", "visible");
      },
      success: function (response) {
        if(!response || response === " "){
          $("#posts-container").append("<p>Nic dalšího tu není!</p>");
          all_posts = true;
        }
        
        $("#posts-container").append(response);
        $("#loader-image").css("visibility", "hidden");
        busy = false;
      }
    });
  }
});


