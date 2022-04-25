$(function(){
    ReloadTable();
});

//table values for queries
var tables = [
    [ "users", "id_u", "username"], //USERS table
    [ "posts", "id_p", "src" ],     //POSTS table
    [ "tags", "id_t", "name" ],     //TAGS table
];

//Searching function
function Search(target){
    var table = GetCurrentTable();
    var search_query = "SELECT * FROM "+ table[0] +" WHERE "+ table[1] +" like '%"+$(target).val()+"%'";
    console.log(search_query);
    var url = "./php/admin_manager.php?search_query="+search_query+"&data_index="+ GetTableDataIndex();
    SendToServer(url, function(response){
        $(".database-data").html(response);
    });
}

//Delete function
function Delete(target){
    var wantsToDelete = confirm("Opravdu chcete vymazat data?");
    if(!wantsToDelete) return;

    var table = GetCurrentTable();
    var id = $(target).parents("tr").attr("id");

    var delete_query = "DELETE FROM "+ table[0] +" WHERE "+ table[1] +" = '" + id + "'";
    var item_query = "SELECT * FROM "+ table[0] +" WHERE "+ table[1] +" ='" + id + "'";

    var url = "./php/admin_manager.php?delete_query="+delete_query+"&item_query="+item_query+"&data_index="+ GetTableDataIndex();
    SendToServer(url, function(response){ReloadTableBody();console.log(response);});
}

//Change data function
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

//Changing table datas
function ChangeTableData(index, target){
    $(".data-table").attr("id", index);
    $(".admin-nav-link").removeClass("active");
    $(target).addClass("active");
    ReloadTable();
}

//Get table
function GetCurrentTable(){
    return tables[GetTableDataIndex()];
}

//Get index of current table
function GetTableDataIndex(){
    return $(".data-table").attr("id");
}

function ReloadTable(){
   ReloadTableHeader();
   ReloadTableBody();
}

function ReloadTableHeader(){
    var table = GetCurrentTable();
    var query = "SELECT * FROM "+ table[0] + ";";
    var url = "./php/admin_manager.php?header_query="+query+"&data_index="+ GetTableDataIndex();
    SendToServer(url, function(response){
        $(".table-header").html(response);
    });
}

function ReloadTableBody(){
    var table = GetCurrentTable();
    var query = "SELECT * FROM "+ table[0] + ";";
    var url = "./php/admin_manager.php?body_query="+query+"&data_index="+ GetTableDataIndex();
    SendToServer(url, function(response){
        $(".database-data").html(response);
        InitializeDataInputs();
    });
}

//Ajax function
function SendToServer(url, success){
    $.ajax({
        type: "GET",
        url: url,
        success: function (response) {
            success(response);
        }
    });
}

