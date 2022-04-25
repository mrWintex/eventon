<?php
function GetUserIconPath($folder_path){
    $icon_folder = dirname(__FILE__, 3)."/". $folder_path . "/icon";
    $file_path = "";
    if(file_exists($icon_folder)){
        $dir = opendir($icon_folder);
        while(false !== ($file = readdir($dir))){
            if(!in_array($file, [".", ".."])){
                $file_path = $folder_path . "/icon/" . $file;
                break;
            }
        }
    }
    else
        $file_path = "./images/icon.jpg";
    return $file_path;
}
?> 