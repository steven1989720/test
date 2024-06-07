<?php
// The user can only use the following HTML tags in messages and only with the following attributes:
// <a href="" title=""> </a>
// <code> </code>
// <i> </i>
// <strike> </strike>
// <strong> </strong>
// There must be a check for closing tags and correct nesting of tags, as well as for XHTML validity.
// The verification logic must be implemented using regular expressions in PHP
// Don't use DOM function.

function validateHTML($html) {
    // Define the regular expression pattern for opening and closing tags
    $pattern = '/<(\/?)\s*([\w]+)\b\s*([^>]*)\s*>/i';

    // Perform the regular expression match
    preg_match_all($pattern, $html, $matches);
    $stack = [];
    
    // Iterate through the matches
    foreach ($matches[0] as $tag) {
        // Extract the tag name and type (opening or closing)
        preg_match($pattern, $tag, $tagInfo);
        $tagName = strtolower($tagInfo[2]);
        $isClosing = $tagInfo[1] === '/';

        // Check if the tag is allowed and if it's a closing tag, verify correct nesting
        if (in_array($tagName, ['a', 'code', 'i', 'strike', 'strong'])) {
            if ($isClosing) {
                // Check if the closing tag matches the last opening tag in the stack
                if (empty($stack) || array_pop($stack) !== $tagName) {
                    echo "<br>Incorrect nesting:".$tagName."<br>";
                    return false; // Incorrect nesting
                }
            } else {
                // Add the opening tag to the stack
                $stack[] = $tagName;
            }
        } else {
            echo "<br>Invalid tag:".$tagName."<br>";
            return false; // Invalid tag
        }

        // Validate attributes for the "a" tag
        if ($tagName === 'a') {
            preg_match_all('/\b([\w]+)\s*(=\s*"[^"]*")?/i', $tagInfo[3], $attributes);
            foreach ($attributes[1] as $index => $attribute) {
                $attribute = strtolower($attribute);
                if (in_array($attribute, ['a', 'href', 'title'])){
                    $value = $attributes[2][$index];
                    if ($value){
                        if ($attribute === 'href') {
                            // Check if href attribute is a valid URL
                            if (preg_match('/=\s*"([^"]*)"/i', $value, $info)){
                                $url = $info[1];
                                if (substr($url, 0, 1) !== "#" && !filter_var($url, FILTER_VALIDATE_URL)) {
                                    echo "<br>Invalid href url:".$url."<br>";
                                    return false; // Invalid href attribute
                                }
                            }
                        }
                    }else{
                        echo "<br>Non href attribute value for ".$attribute."<br>";
                        return false; // Invalid href attribute
                    }
                }else{
                    echo "<br>Invalid a attribute:".$attribute."<br>";
                    return false;
                }
            }
        }
    }

    // Ensure all opening tags have matching closing tags
    if (!empty($stack)){
        echo "<br>Tag is not closed:".array_pop($stack)."<br>";
        return false;
    }
    return true;
}

function highlightWords($text, $array_of_words) {
    // Sort the array by string length in descending order to match longer words first
    usort($array_of_words, function($a, $b) {
        return mb_strlen($b, 'UTF-8') - mb_strlen($a, 'UTF-8');
    });

    foreach ($array_of_words as $word) {
        // Use the \b boundary to match whole words and 'i' modifier for case-insensitive search
        $pattern = '/\b' . preg_quote($word, '/') . '\b/iu';
        // Use a callback function to ensure only the first occurrence is replaced
        $text = preg_replace_callback($pattern, function($matches) {
            static $replacedWords = [];
            if (!in_array(strtolower($matches[0]), $replacedWords)) {
                $replacedWords[] = strtolower($matches[0]);
                return '[' . $matches[0] . ']';
            }
            return $matches[0];
        }, $text);
    }

    return $text;
}

// Example usage
$html = '< strong >This is <i>italic</i> and <a href="#" title="Link">bold</a></strong><     a href="http://hh.ru" title="Link33" >bold</a>';
if (validateHTML($html)) {
    echo "<br>HTML is valid<br>";
} else {
    echo "<br>HTML is not valid<br>";
}

// $text = "Mama washed the frame";
$text = "Необходимо выделить первые вхождения каждого из слов с помощью квадратных скобок";
$array_of_words = ["делить", "с", "выделить"];
echo highlightWords($text, $array_of_words);

?>