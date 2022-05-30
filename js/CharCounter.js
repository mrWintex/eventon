$(function() {
    max_length = 300;
    ChangeCounterText(0);
    $("#comment").on("input", function() {
        $(this).val($(this).val().substr(0, max_length));
        ChangeCounterText($(this).val().length);
    });
});

function ChangeCounterText(amount){
    $("#char-counter-text").html(amount + "/" + max_length);
}