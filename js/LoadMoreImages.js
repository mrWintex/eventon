$(document).ready(function () {
  var busy = false;
  var all_posts = false;
  var offset = 50;
  $(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() >= $(document).height() - offset && !busy && !all_posts){
      busy = true;
      var id = $(".post-container").last().attr("id");
      var query = "SELECT * FROM posts P JOIN users U ON P.user_owner = U.id_u WHERE (P.id_p < " + id + ") ORDER BY P.id_p DESC LIMIT 2";

      $.ajax({
        type: "GET",
        url: "./php/postmanager.php?query="+query,
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
});
