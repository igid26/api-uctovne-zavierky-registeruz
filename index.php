<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .do-plusu,
    .class-do-plusu {
      color:green;  
    }
    .do-minusu,
    .class-do-minusu {
      color:red;  
    }
    </style>
</head>
<body>
    
<?php 
if(!empty($_GET["ico"])) {
$ico = $_GET["ico"];

//vyhladanie či sa výraz nachádza v cykle
function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}


//opakujúca sa API funkcia, ktorá prebieha jednotlívými URL, ktoré slúžia na získavanie údajov
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

//Generovanie HTML (názov celku,id riadku vo výstupe, id riadku vo výstupe, $key z cyklu, $value z cyklu )
function generovanieHTML($nazov, $novy_rok, $stary_rok, $kluc, $key, $value) {
if($key === $kluc) {
if($novy_rok < $stary_rok) { // porovnanie výsledkov medzi daným rokom a prechádzajúcim - podmienka slúži na znázornenie či ide subjekt do mínuových čísel. 
$class_farba = 'class-do-minusu';
} else {
$class_farba = 'class-do-plusu';
}
?>
<div class="w-16 d-inline-block float-left polozka-overenie <?php echo $class_farba; ?>"> 
<?php
echo '<div class="w-100 d-block float-left f-size-18 f-weight-300 titulokpolozka-overenie"><span>' . $nazov .'</span></div>'; //Zobrazenie názvu
if(is_numeric($value)) {
echo '<strong>'. number_format($value, 0, ',', ' ') . ' €</strong>'; // Zobrazenie hodnoty
} else {
echo '<strong>-</strong>';    
}
if($novy_rok < $stary_rok) {echo'<div class="znamienko do-minusu d-inline-block">&#8595;</div>';} else {echo'<div class="znamienko do-plusu d-inline-block">&#8593;</div>';}
echo '</div>';
}
}

$link ="https://www.registeruz.sk/cruz-public/api/uctovne-jednotky?zmenene-od=2000-01-01&max-zaznamov=100&ico=$ico";
    $obj = getJsonData($link);
    $id_uctovnej_jednotky = implode("", $obj['id']);

    $link_uctovna_jednotka = "https://www.registeruz.sk/cruz-public/api/uctovna-jednotka?id=$id_uctovnej_jednotky";
    $obj_new = getJsonData($link_uctovna_jednotka);

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


if(!empty($obj_new4['obsah']['tabulky'])) {
foreach($tabulky as $tabulkys)  {

if (in_array_r("Výkaz ziskov a strát", $tabulkys)) {

$previousValue = null;
foreach ($tabulkys as $tabulkyss)  {

foreach($tabulkyss as $key => $value) {


//TRZBY ZACIATOK
if(!empty($tabulkys['data'][4])) {  // Pri niektorých iteráciach sú údaje na riadkoch 4 a 5, ale pre niektorých to môžu byť riadky 0 a 1, preto som to vložil do podmienky
generovanieHTML('Tržby', $tabulkys['data'][4], $tabulkys['data'][5], 4,  $key, $value);
} else {
generovanieHTML('Tržby', $tabulkys['data'][0], $tabulkys['data'][1], 0,  $key, $value);    
}
//TRZBY KNIEC


//ZISK Zaciatok
$class_farba = '';
if(!empty($tabulkys['data'][74])) { // Pri niektorých iteráciach sú údaje na riadkoch 74 a 75, ale pre niektorých to môžu byť riadky 120 a 121, preto som to vložil do podmienky
    generovanieHTML('Zisk', $tabulkys['data'][74], $tabulkys['data'][75], 74,  $key, $value);
} else {
    generovanieHTML('Zisk', $tabulkys['data'][120], $tabulkys['data'][121], 120,  $key, $value);
}

//Celkové výnosy

if(!empty($tabulkys['data'][2])) { // Pri niektorých iteráciach sú údaje na riadkoch 2 a 3, ale pre niektorých to môžu byť riadky 0 a 1, preto som to vložil do podmienky
    generovanieHTML('celkové výnosy', $tabulkys['data'][2], $tabulkys['data'][3], 2,  $key, $value);
} else {
    generovanieHTML('celkové výnosy', $tabulkys['data'][0], $tabulkys['data'][1], 0,  $key, $value);
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

generovanieHTML('celkový majetok', $tabulkys['data'][2], $tabulkys['data'][3], 2,  $key, $value);

generovanieHTML('Nerozdelený zisk', $tabulkys['data'][16], $tabulkys['data'][17], 16,  $key, $value);


//Záväzky
if(!empty($tabulkys['data'][20])) {
    generovanieHTML('Závätky', $tabulkys['data'][20], $tabulkys['data'][21], 20,  $key, $value);

} else {
generovanieHTML('Závätky', $tabulkys['data'][44], $tabulkys['data'][45], 44,  $key, $value);
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

</body>
</html>