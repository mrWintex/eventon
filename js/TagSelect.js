$(function(){   
    $tag_input = $(".tag-input");
    $tag_autocomplete = $(".tag-autocomplete");
    $tag_container = $(".tag-container");

    $tag_input.on("input", function(){
        $value = (25 + (8 * $(this).val().length)) + "px";
        $(this).css("width", $value);
        ReloadTagsList($(this).val())
      }).on("focusin", function(){
        $tag_autocomplete.css("display", "block");
      }).on("focusout", function(){
        setTimeout(function(){
          $tag_autocomplete.css("display", "none");
        }, 200);
      });

    $tag_container.on("click", () => {$tag_input.focus();$tag_autocomplete.css("display", "block");});
});

function RemoveTag(target){
    $(target).closest(".tag").remove();

}

function ReloadTagsList(tag_search){
    $.ajax({
        type: "GET",
        url: "./php/post_search_manager.php?tag_search="+tag_search,
        success: function(r) {
            $(".tag-autocomplete").html(r);
            CheckTagList();
            $(".tag-autocomplete-item").on("click", function(){
                $('<div class="tag"><span class="text">'+ $(this).text() +'</span><button onclick="RemoveTag(this)"class="tag-delete-button" type="button"><i class="fa-solid fa-xmark"></i></button></div>').insertBefore(".tag-input");
                $tag_input.val("");
                $tag_input.focus();
                $(this).off();
                $tag_autocomplete.html("");
            });
        }
    });
}

function CheckTagList(){
    $used_tags = $(".tag-select .tag");
    $(".tag-autocomplete-item").each(function(){
        for(var i = 0; i < $used_tags.length; i++){
          if($(this).text() == $($used_tags[i]).text()) $(this).remove();
        }
    });
}