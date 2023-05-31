<?php
// Read the JSON file
$json = file_get_contents('translationsconfirmed.json');
$data = json_decode($json, true);
$lang_one = '';
$lang_two = '';
// Retrieve data based on user input
$input_word = isset($_POST['input_word']) ? $_POST['input_word'] : '';
$inputlanguage = isset($_POST['inputlanguage']) ? $_POST['inputlanguage'] : 'tokodede';
if (isset($data[$inputlanguage][$input_word])) {
	switch ($inputlanguage) {
		case 'tokodede':
			$translation_one = $data['tokodede'][$input_word]['english_translation'];
			$translation_two = $data['tokodede'][$input_word]['tetun_translation'];
			$lang_one = 'english';
			$lang_two = 'tetun';
			break;
		case 'tetun':
			$translation_one = $data['tetun'][$input_word]['tokodede_translation'];
			$translation_two = $data['tetun'][$input_word]['english_translation'];
			$lang_one = 'tokodede';
			$lang_two = 'english';
			break;
		case 'english':
			$translation_one = $data['english'][$input_word]['tokodede_translation'];
			$translation_two = $data['english'][$input_word]['tetun_translation'];
			$lang_one = 'tokodede';
			$lang_two = 'tetun';
			break;
	}
}
else {
	$translation_one = '';
	$translation_two = '';
	$lang_one = '';
	$lang_two = '';
}
// Return JSON response
$response = array(
	'translation_one' => $translation_one,
	'translation_two' => $translation_two,
	'lang_one' => $lang_one,
	'lang_two' => $lang_two,
);
echo json_encode($response);
?>