function OpenPost(target) {
    if($(window).innerWidth() < 1500)
        return;

    var container = $(target).closest(".post-container")[0];
    var wrapper = $(target).closest(".post-wrapper")[0];
    $(".post-container.active-post").filter(function( index ) {return $(this)[index] !== container;}).removeClass("active-post");
    $(container).toggleClass("active-post");
    if($(container).hasClass("active-post")){
        $('html, body').animate({
            scrollTop: ($(wrapper).offset().top - (($(window).outerHeight() + $(".navigation").outerHeight(true)) - $(wrapper).outerHeight(true))/2)
        }, 500);
    }
    
};

function OpenImage(target){
    window.location.href = $(target).find("img").attr("src");
}

function LikePost(target){
    console.log("!");
    var post_id = $(target).parents(".post-container").attr("id");

    var like_text = $(target).parent().find("span");
    if(!$(target).parent().hasClass("liked")){
        like_text.html(parseInt(like_text.text()) + 1);
        $(target).parent().addClass("liked");
    }
    else if($(target).parent().hasClass("liked")){
        $(target).parent().removeClass("liked");
        like_text.html(parseInt(like_text.text()) - 1);
    }
    $.ajax({
        type: "GET",
        url: "./php/postmanager.php?postid="+post_id,
    });
}

function ScrollToTop(){
    $('html, body').animate({
        scrollTop: 0
    }, 1000);
}