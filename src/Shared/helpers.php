<?php 

function headerAdmin($data=""){
    require_once "../src/Infrastructure/Web/Views/shared/header_admin.php";
}

function footerAdmin($data=""){
    require_once "../src/Infrastructure/Web/Views/shared/footer_admin.php";
}

function media(){
    $ruta = BASE_URL."/public/assets";

    return $ruta;
}

function base_url(){
    $ruta = BASE_URL;
    return $ruta;
}

?>