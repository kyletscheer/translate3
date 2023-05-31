<!DOCTYPE html>
<html>
<head>
    <title>Translation Form</title>
    <link rel="stylesheet" type="text/css" href="style.css">
	<script src="levenshtein.js"></script>
	<script>
		var inputLanguage = 'tokodede';
	</script>
</head>
<body>
	<header>
		<h1>Tokodede/Tetun/English Translator</h1>
		<button><a class="link" href="tokodedecsv.csv">All translations</a></button>
	</header>
	<div id="translate_buttons">
		<button id="tokodedeButton" onclick="setInputLanguage('tokodede')">Tokodede</button>
		<button id="englishButton" onclick="setInputLanguage('english')">English</button>
		<button id="tetunButton" onclick="setInputLanguage('tetun')">Tetun</button>
	</div>
	<br>
    <label for="userInput" id="inputLanguageLabel"><span id="inputLanguageValue"></span></label>
	<input type="text" id="userInput" onkeyup="updateTranslation()" placeholder="Enter text here..."><br>
	<i>Did you mean: <span id="spellcheckSuggestions"></span></i><br>
    <span id="translationOne"></span><br>
    <span id="translationTwo"></span><br>

<script>
        function setInputLanguage(language) {
            inputLanguage = language;
			var inputLanguageValue = document.getElementById("inputLanguageValue");
			if (inputLanguageValue) {
				inputLanguageValue.textContent = inputLanguage;
			}
			document.getElementById("inputLanguageLabel").textContent = 'Enter ' + inputLanguage + ' Word:';
			// repopulate validWords based on the new inputLanguage
			var data = JSON.parse(xhr.responseText)[inputLanguage];
			  window.validWords = {};
			for (var key in data) {
				validWords[key] = true;
			}
		}
		console.log(inputLanguage);
        // Load the JSON file containing the valid translations
		window.validWords = {};
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "translationsconfirmed.json", true);
        xhr.onreadystatechange = function() {
		  if (xhr.readyState == 4 && xhr.status == 200) {
			window.validWords = {};
			var data = JSON.parse(xhr.responseText)[inputLanguage];
			for (var key in data) {
			  validWords[key] = true;
			}
		  }
		
		/*	if (xhr.readyState == 4 && xhr.status == 200) {
				var data = JSON.parse(xhr.responseText);
				if (data[inputlanguage]) {
					var inputLanguageWords = data[inputlanguage];
					for (var prop in inputLanguageWords) {
						window.validWords[prop] = inputLanguageWords[prop];
					}
				}
			}*/
        };
        xhr.send();
		function updateTranslation() {
            // Retrieve user input
            var userInput = document.getElementById("userInput").value;
            // Send user input to server via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "translate.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
					// Check for spelling errors and suggest corrections
                    var spellcheckSuggestions = "";
                    for (var word in validWords) {
                        var distance = levenshteinDistance(userInput, word);
                        if (distance === 1) {
                            spellcheckSuggestions += '<a href="#" onclick="document.getElementById(\'userInput\').value=\'' + word + '\'; updateTranslation();">' + word + '</a>, ';
                        }
                    }
					// Remove the final comma if there are any suggestions
					if (spellcheckSuggestions !== "") {
						spellcheckSuggestions = spellcheckSuggestions.slice(0, -2);
					}
                    document.getElementById("spellcheckSuggestions").innerHTML = spellcheckSuggestions;
					
                    // Update translation based on server response
                    var response = JSON.parse(xhr.responseText);
                    //document.getElementById("translationOne").innerHTML = response.lang_one + ' translation: ' + response.translation_one;
                    document.getElementById("translationOne").innerHTML = response.lang_one + ' translation: ' + '<a href="#" onclick="document.getElementById(\'userInput\').value=\'' + response.translation_one + '\'; updateTranslation(); setInputLanguage(\'' + response.lang_one + '\')">' + response.translation_one + '</a>';
                    //document.getElementById("translationTwo").innerHTML = response.lang_two + ' translation: ' + response.translation_two;
					document.getElementById("translationTwo").innerHTML = response.lang_two + ' translation: ' + '<a href="#" onclick="document.getElementById(\'userInput\').value=\'' + response.translation_two + '\'; updateTranslation(); setInputLanguage(\'' + response.lang_two + '\')">' + response.translation_two + '</a>';
				}
            };
            xhr.send("input_word=" + encodeURIComponent(userInput) + "&inputlanguage=" + encodeURIComponent(inputLanguage));
        }
		    // Calculate the Levenshtein distance between two strings
        function levenshteinDistance(str1, str2) {
            var matrix = [];
            for (var i = 0; i <= str2.length; i++) {
                matrix[i] = [i];
            }
            for (var j = 0; j <= str1.length; j++) {
                matrix[0][j] = j;
            }
            for (var i = 1; i <= str2.length; i++) {
                for (var j = 1; j <= str1.length; j++) {
                    if (str2.charAt(i-1) == str1.charAt(j-1)) {
                        matrix[i][j] = matrix[i-1][j-1];
                    } else {
                        matrix[i][j] = Math.min(matrix[i-1][j-1] + 1,
                                                matrix[i][j-1] + 1,
                                                matrix[i-1][j] + 1);
                    }
                }
            }
            return matrix[str2.length][str1.length];
        }
</script>
</body>
</html>
