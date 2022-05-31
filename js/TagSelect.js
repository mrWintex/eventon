$(function () {
  //proměnné
  $tag_input = $(".tag-input");
  $tag_autocomplete = $(".tag-autocomplete");
  $tag_container = $(".tag-container");
  $number_of_tags = 0;

  //po kliknutí na vyhledávač a psaní do něj
  //- se dynamicky zvětšuje šířka vyhledávače
  //- načítá se výsledek vyhledávání
  //po kliknutí mimo vyhledávač se schová 
  $tag_input.on("input", function () {
    $value = (25 + (8 * $(this).val().length)) + "px";
    $(this).css("width", $value);
    if ($(this).val() === "") $tag_autocomplete.html("");
    else ReloadTagsList($(this).val());
  }).on("focusin", function () {
    $tag_autocomplete.css("display", "block");
  });

  //po kliknutí na stránce, se schová výsledek vyhledávání
  $(document).on("click", function (e) {
    if ($(e.target).closest(".tag-container").length || $(e.target).is(".tag-autocomplete-item") || $(e.target).closest(".tag-delete-button").length) return;
    $tag_autocomplete.css("display", "none");
  });

  $tag_container.on("click", () => $tag_input.focus());
});

//Odebere tag
function RemoveTag(target) {
  $(target).closest(".tag").remove();
  $("input[value="+$(target).closest(".tag").attr("id")+"]").remove();
  $number_of_tags--;
  ReloadTagsList();
}

//Načte výsledek vyhledávání
function ReloadTagsList(tag_search) {
  var jsonObj = JSON.stringify(GetTagList());
  $.ajax({
    type: "POST",
    url: "./php/post_search_manager.php",
    data: {
      tag_search: tag_search,
      used_tags: jsonObj,
    },
    success: function (r) {
      $(".tag-autocomplete").html(r);
      $(".tag-autocomplete-item").on("click", function () {
        if($number_of_tags + 1 > 10) return;
        $number_of_tags++;
        if($(this).hasClass("create-item")){
          $.ajax({
            type: "POST",
            url: "./php/tag_manager.php",
            data: {
              new_tag: $tag_input.val(),
            },
            success: (r) => {
              $(r).insertBefore($tag_input);
            },
          });
        }
        else{
          $('<input type="hidden" name="selected-tag['+$(this).attr("id")+']" value='+$(this).attr("id")+'>').insertBefore($tag_input);
          $('<div class="tag" id='+$(this).attr("id")+'><span class="text">' + $(this).text() + '</span><button onclick="RemoveTag(this)"class="tag-delete-button" type="button"><i class="fa-solid fa-xmark"></i></button></div>').insertBefore(".tag-input");
        }
        $tag_input.val("");
        $tag_autocomplete.html("");
      });
    }
  });
}

//Vrátí pole použitých tagů
function GetTagList() {
  $used_tags = [];
  $(".tag-select .tag").each(function () {
    $used_tags.push($(this).text());
  });
  return $used_tags;
}
