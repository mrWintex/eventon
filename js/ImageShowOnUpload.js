$(function ($) {  
    $("#image").on("change", function()
    {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return;
        if (/^image/.test( files[0].type)){
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);
            
            reader.onloadend = function(){
                $("#imagePreview").html("<img class='image-preview' src="+this.result+" alt='image-preview'>")
            }
        }
    });
});