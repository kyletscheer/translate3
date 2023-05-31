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
