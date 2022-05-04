$(function(){
    ReloadTable();
});

//Data pro dotazy
var tables = [
    [ "users", "id_u", "username"], //USERS table
    [ "posts", "id_p", "src" ],     //POSTS table
    [ "tags", "id_t", "name" ],     //TAGS table
];

var table_items = [
    ["id_u", "username", "email", "reg_date", "admin"],  //USERS table
    ["id_p", "src", "comment", "add_date", "user_owner"],   //POSTS table
    ["id_t", "name"],                                       //TAGS table
];

//Vyhledávací funkce
function Search(target){
    var table = GetCurrentTable();
    var search_query = "SELECT "+ GetTableItems() +" FROM "+ table[0] +" WHERE "+ table[1] +" like '"+$(target).val()+"'";
    console.log(search_query);
    var url = "./php/admin_manager.php?search_query="+search_query+"&data_index="+ GetTableDataIndex();
    SendToServer(url, function(response){
        $(".database-data").html(response);
        InitializeDataInputs();
    });
}

//Odstraňovací funkce
function Delete(target){
    var wantsToDelete = confirm("Opravdu chcete vymazat data?");
    if(!wantsToDelete) return;

    var table = GetCurrentTable();
    var id = $(target).parents("tr").attr("id");

    var delete_query = "DELETE FROM "+ table[0] +" WHERE "+ table[1] +" = '" + id + "'";
    var item_query = "SELECT "+ GetTableItems() +" FROM "+ table[0] +" WHERE "+ table[1] +" ='" + id + "'";
    console.log(item_query);

    var url = "./php/admin_manager.php?delete_query="+delete_query+"&item_query="+item_query+"&data_index="+ GetTableDataIndex();
    SendToServer(url, function(response){ReloadTableBody();console.log(response);});
}

//Funkce pro změnu dat
function InitializeDataInputs(){
    $(".datachange-input").on("focusin", function(){
        $(this).data("val", $(this).val());
    }).on("change", function(){
        if(!confirm("Chcete přepsat tato data?")) $(this).val($(this).data("val"));
        else{
            var table = GetCurrentTable();
            var query = "UPDATE "+ table[0] +" SET "+ $(this).attr("name") +" = '"+ $(this).val() +"' WHERE "+ table[1] +" = '"+ $(this).parents("tr").attr("id") +"'";
            var url = "./php/admin_manager.php?change_query="+query;
            SendToServer(url, (r)=>{});
        }
    });
}

//Přechod mezi tabulky
function ChangeTableData(index, target){
    $(".data-table").attr("id", index);
    $(".admin-nav-link").removeClass("active");
    $(target).addClass("active");
    ReloadTable();
}

//Získání dat aktuálně zobrazené tabulky
function GetCurrentTable(){
    return tables[GetTableDataIndex()];
}

//Získání indexu právě zobrazené tabulky
function GetTableDataIndex(){
    return $(".data-table").attr("id");
}

//Získání potřebných sloupců pro vygenerivání dotazu
function GetTableItems(){
    var items_string = "";
    table_items[GetTableDataIndex()].forEach(item => {
        items_string += item + ", ";
    });
    return items_string.substring(0, items_string.length - 2);
}

//Obnovení celé tabulky
function ReloadTable(){
   ReloadTableHeader();
   ReloadTableBody();
}

//Obnovení hlavičky tabulky
function ReloadTableHeader(){
    var table = GetCurrentTable();
    var query = "SELECT "+ GetTableItems() +" FROM "+ table[0] + ";";
    var url = "./php/admin_manager.php?header_query="+query+"&data_index="+ GetTableDataIndex();
    SendToServer(url, function(response){
        $(".table-header").html(response);
    });
}

//Obnovení těla tabulky
function ReloadTableBody(){
    var table = GetCurrentTable();
    var query = "SELECT "+ GetTableItems() +" FROM "+ table[0] + ";";
    var url = "./php/admin_manager.php?body_query="+query+"&data_index="+ GetTableDataIndex();
    SendToServer(url, function(response){
        $(".database-data").html(response);
        InitializeDataInputs();
    });
}

//funkce AJAX s callback funkcí
function SendToServer(url, success){
    $.ajax({
        type: "GET",
        url: url,
        success: function (response) {
            success(response);
        }
    });
}

