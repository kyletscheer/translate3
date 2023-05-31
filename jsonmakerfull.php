<?php
$csvFile = 'tokodedecsv.csv';
$csv = array_map('str_getcsv', file($csvFile));
$header = $csv[0];
$data = array_slice($csv, 1);

$json = array(
    'tokodede' => array(),
    'tetun' => array(),
    'english' => array()
);

foreach ($data as $row) {
    $tokodede = $row[0];
    $tetun = $row[1];
    $english = $row[2];

    // Add tokodede key with tetun and english translations below it
    $json['tokodede'][$tokodede] = array(
        'tetun_translation' => $tetun,
        'english_translation' => $english
    );

    // Add tetun key with tokodede and english translations below it
    $json['tetun'][$tetun] = array(
        'tokodede_translation' => $tokodede,
        'english_translation' => $english
    );

    // Add english key with tokodede and tetun translations below it
    $json['english'][$english] = array(
        'tokodede_translation' => $tokodede,
        'tetun_translation' => $tetun
    );
}

$jsonString = json_encode($json, JSON_PRETTY_PRINT);
echo $jsonString;
?>
