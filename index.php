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


$link ="https://www.registeruz.sk/cruz-public/api/uctovne-jednotky?zmenene-od=2000-01-01&max-zaznamov=100&ico=$ico";


$curl=curl_init(); 
$key = "Cache-Control: max-age=3600";
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($curl, CURLOPT_HTTPHEADER, array($key,"Content-Type:application/json", "Accept:*/*", "page: 1"));  
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,1);
$out = curl_exec($curl); 
curl_close($curl);
$obj = json_decode($out);
$id_uctovnej_jednotky = implode("",$obj->id);


$link_uctovna_jednotka ="https://www.registeruz.sk/cruz-public/api/uctovna-jednotka?id=$id_uctovnej_jednotky";

$curl2=curl_init(); 
$key = "Cache-Control: max-age=3600";
curl_setopt($curl2,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl2,CURLOPT_URL,$link_uctovna_jednotka);
curl_setopt($curl2,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($curl2, CURLOPT_HTTPHEADER, array($key,"Content-Type:application/json", "Accept:*/*", "page: 1"));  
curl_setopt($curl2,CURLOPT_SSL_VERIFYPEER,1);
$out2 = curl_exec($curl2); 
curl_close($curl2);
$obj_new = json_decode($out2);



//echo $obj_new->skNace;
//echo $obj_new->konsolidovana;
//echo $obj_new->nazovUJ;
//print_r($obj_new);
$id_vykazu = array();
rsort($obj_new->idUctovnychZavierok);
foreach ($obj_new->idUctovnychZavierok as $ustovna_zavierka) {



$link_uctovna_zavierka ="https://www.registeruz.sk/cruz-public/api/uctovna-zavierka?id=$ustovna_zavierka";

$curl3=curl_init(); 
$key = "Cache-Control: max-age=3600";
curl_setopt($curl3,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl3,CURLOPT_URL,$link_uctovna_zavierka);
curl_setopt($curl3,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($curl3, CURLOPT_HTTPHEADER, array($key,"Content-Type:application/json", "Accept:*/*", "page: 1"));  
curl_setopt($curl3,CURLOPT_SSL_VERIFYPEER,1);
$out3 = curl_exec($curl3); 
curl_close($curl3);
$obj_new2 = json_decode($out3);


$id_vykazu = $obj_new2->idUctovnychVykazov;


foreach ($id_vykazu as $ustovnay_vykaz) {  



$link_uctovny_vykaz ="https://www.registeruz.sk/cruz-public/api/uctovny-vykaz?id=$ustovnay_vykaz";

$curl4=curl_init(); 
$key = "Cache-Control: max-age=3600";
curl_setopt($curl4,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl4,CURLOPT_URL,$link_uctovny_vykaz);
curl_setopt($curl4,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($curl4, CURLOPT_HTTPHEADER, array($key,"Content-Type:application/json", "Accept:*/*", "page: 1"));  
curl_setopt($curl4,CURLOPT_SSL_VERIFYPEER,1);
$out4 = curl_exec($curl4); 
curl_close($curl4);
$obj_new4_zakl = json_decode( json_encode($out4), true);
//$obj_new4 = json_decode( $out4, true);
$obj_new4 = json_decode($out4, true);


//print_r($obj_new4);
$tabulky = '';
$datumPoslednejUpravyod = '';
$datumPoslednejUpravydo = '';
if(!empty($obj_new4['obsah']['tabulky'])) {
$tabulky = $obj_new4['obsah']['tabulky'];
}

$ulozenie_tabuliek[] = $obj_new4;
if(!empty($obj_new4['obsah']['titulnaStrana']['obdobieOd'])) {
$datumPoslednejUpravyod = $obj_new4['obsah']['titulnaStrana']['obdobieOd'];
}
if(!empty($obj_new4['obsah']['titulnaStrana']['obdobieDo'])) {
$datumPoslednejUpravydo = $obj_new4['obsah']['titulnaStrana']['obdobieDo'];
}

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
if($key === 4) {
if($tabulkys['data'][4] < $tabulkys['data'][5]) {
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>">
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>Tržby</span></div>';
echo '<strong>'. number_format($value, 0, ',', ' ') . ' €</strong>';
if($tabulkys['data'][4] < $tabulkys['data'][5]) {echo'<div class="znamienko do-minusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';} else {echo'<div class="znamienko do-plusu d-inline-block"><i class="flaticon flaticon-down-arrow"></i></div>';}
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