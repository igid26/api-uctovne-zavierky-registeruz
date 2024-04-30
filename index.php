<?php 
$ico = $_GET["ico"];
if(!empty($ico)) {

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}


function getJsonData($link) {
    $curl = curl_init();
    $key = "Cache-Control: max-age=3600";
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array($key, "Content-Type:application/json", "Accept:*/*", "page: 1"));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
    $out = curl_exec($curl);
    curl_close($curl);
    return json_decode($out, true);
}

function generovanieHTML($novy_rok, $stary_rok, $kluc) {
if($key === $kluc) {
if($novy_rok < $stary_rok) {
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>">
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>Tržby</span></div>';
echo '<strong>'. number_format($value, 0, ',', ' ') . ' €</strong>';
if($novy_rok < $stary_rok) {echo'<div class="znamienko do-minusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';} else {echo'<div class="znamienko do-plusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';}
echo '</div>';
}


}

$link ="https://www.registeruz.sk/cruz-public/api/uctovne-jednotky?zmenene-od=2000-01-01&max-zaznamov=100&ico=$ico";
    $obj = getJsonData($link);
    $id_uctovnej_jednotky = implode("", $obj['id']);

    $link_uctovna_jednotka = "https://www.registeruz.sk/cruz-public/api/uctovna-jednotka?id=$id_uctovnej_jednotky";
    $obj_new = getJsonData($link_uctovna_jednotka);


//echo $obj_new->skNace;
//echo $obj_new->konsolidovana;
//echo $obj_new->nazovUJ;
//print_r($obj_new);
$id_vykazu = array();
rsort($obj_new['idUctovnychZavierok']);
foreach ($obj_new['idUctovnychZavierok'] as $ustovna_zavierka) {



$link_uctovna_zavierka = "https://www.registeruz.sk/cruz-public/api/uctovna-zavierka?id=$ustovna_zavierka";
        $obj_new2 = getJsonData($link_uctovna_zavierka);
        $id_vykazu = $obj_new2['idUctovnychVykazov'];


foreach ($id_vykazu as $ustovnay_vykaz) {
            $link_uctovny_vykaz = "https://www.registeruz.sk/cruz-public/api/uctovny-vykaz?id=$ustovnay_vykaz";
            $obj_new4 = getJsonData($link_uctovny_vykaz);

            $tabulky = !empty($obj_new4['obsah']['tabulky']) ? $obj_new4['obsah']['tabulky'] : [];
            $datumPoslednejUpravyod = !empty($obj_new4['obsah']['titulnaStrana']['obdobieOd']) ? $obj_new4['obsah']['titulnaStrana']['obdobieOd'] : '';
            $datumPoslednejUpravydo = !empty($obj_new4['obsah']['titulnaStrana']['obdobieDo']) ? $obj_new4['obsah']['titulnaStrana']['obdobieDo'] : '';


//echo $obj_new4['obsah']['titulnaStrana']['typZavierky'];

if(!empty($obj_new4['obsah']['tabulky'])) {
foreach($tabulky as $tabulkys)  {


if (in_array_r("Výkaz ziskov a strát", $tabulkys)) {
//print_r($tabulkys);




$previousValue = null;
foreach ($tabulkys as $tabulkyss)  {


foreach($tabulkyss as $key => $value) {


//TRZBY ZACIATOK
if(!empty($tabulkys['data'][4])) {
generovanieHTML($tabulkys['data'][4], $tabulkys['data'][5], 4);
} else {
if($key === 0) {
if($tabulkys['data'][0] < $tabulkys['data'][1]) {
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>">
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>Tržby</span></div>';
echo '<strong>'. number_format($value, 0, ',', ' ') . ' €</strong>';
if($tabulkys['data'][0] < $tabulkys['data'][1]) {echo'<div class="znamienko do-minusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';} else {echo'<div class="znamienko do-plusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';}
echo '</div>';
}
}
//TRZBY KNIEC






//ZISK Zaciatok
$class_farba = '';
if(!empty($tabulkys['data'][74])) {
if($key === 74) { 

if($tabulkys['data'][74] < $tabulkys['data'][75]) {
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>">
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>Zisk</span></div>';
echo '<strong>'. number_format($value, 0, ',', ' ') . ' €</strong>';
if($tabulkys['data'][74] < $tabulkys['data'][75]) {echo'<div class="znamienko do-minusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';} else {echo'<div class="znamienko do-plusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';}
echo '</div>';
}
} else {
if($key === 120) { 

if($tabulkys['data'][120] < $tabulkys['data'][121]) {
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>">
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>Zisk</span></div>';
echo '<strong>'. number_format($value, 0, ',', ' ') . ' €</strong>';
if($tabulkys['data'][120] < $tabulkys['data'][121]) {echo'<div class="znamienko do-minusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';} else {echo'<div class="znamienko do-plusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';}
echo '</div>';
}
}





//ZISK Celkové výnosy

if(!empty($tabulkys['data'][2])) {
if($key === 2) {
if($tabulkys['data'][2] < $tabulkys['data'][3]) {
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>">
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>Celkové výnosy</span></div>';
echo '<strong>'. number_format($tabulkys['data'][2], 0, ',', ' ') . ' €</strong>';
if($tabulkys['data'][2] < $tabulkys['data'][3]) {echo'<div class="znamienko do-minusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';} else {echo'<div class="znamienko do-plusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';}
echo '</div>';
}
} else {
if($key === 0) {
if($tabulkys['data'][0] < $tabulkys['data'][1]) {
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>">
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>Celkové výnosy</span></div>';
echo '<strong>'. number_format($tabulkys['data'][0], 0, ',', ' ') . ' €</strong>';
if($tabulkys['data'][0] < $tabulkys['data'][1]) {echo'<div class="znamienko do-minusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';} else {echo'<div class="znamienko do-plusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';}
echo '</div>';
}
}



}

}

echo '</div>';
echo '</div>';
//Koniec panelu
} 


if (in_array_r("Strana pasív", $tabulkys)) {

echo '<div class="w-100 d-block float-left">';

echo '<div style="width:100%;height:2px;background:#000;"></div>';
echo  '<strong class="w-100 d-block float-left mb-1 f-size-20">Rok: ' . date('Y',strtotime($datumPoslednejUpravydo)) . '</strong>';

echo '<div class="w-100 d-block float-left biele tien-boxu mb-5 p-4 box-sizizng">';

//print_r($tabulkys);

foreach ($tabulkys as $tabulkyss)  {

foreach($tabulkyss as $key => $value) {


if($key === 2) {
if($tabulkys['data'][2] < $tabulkys['data'][3]) {
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>">
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>Celkový majetok</span></div>';
echo '<strong>'. number_format($value, 0, ',', ' ') . ' €</strong>';
if($tabulkys['data'][2] < $tabulkys['data'][3]) {echo'<div class="znamienko do-minusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';} else {echo'<div class="znamienko do-plusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';}
echo '</div>';

}

if($key === 16) {
if($tabulkys['data'][16] < $tabulkys['data'][17]) {
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>">
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>Nerozdelený zisk</span></div>';
if(!empty($value)) { echo '<strong>'.  number_format($tabulkys['data'][16], 0, ',', ' ') . ' €</strong>'; } 
if($tabulkys['data'][16] < $tabulkys['data'][17]) {echo'<div class="znamienko do-minusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';} else {echo'<div class="znamienko do-plusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';}

echo '</div>';
}




if(!empty($tabulkys['data'][20])) {
if($key === 20) {
if($tabulkys['data'][20] > $tabulkys['data'][21]) {
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>">
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>Záväzky</span></div>';
echo '<strong>'. number_format($tabulkys['data'][20], 0, ',', ' ') . ' €</strong>';
if($tabulkys['data'][20] > $tabulkys['data'][21]) {echo'<div class="znamienko do-minusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';} else {echo'<div class="znamienko do-plusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';}

echo '</div>';
}
} else {
if($key === 44) {
if($tabulkys['data'][44] > $tabulkys['data'][45]) {
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>">
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>Záväzky</span></div>';
echo '<strong>'. number_format($tabulkys['data'][44], 0, ',', ' ') . ' €</strong>';
if($tabulkys['data'][44] > $tabulkys['data'][45]) {echo'<div class="znamienko do-minusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';} else {echo'<div class="znamienko do-plusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';}

echo '</div>';
}
}

}

}
//Koniec panelu
} 
}
}

}
}

}
?>


<form method="get">
    <input type="text" name="ico">
    <input type="submit" value="Odoslať">
</form>