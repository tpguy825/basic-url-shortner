<?php

// Load config.json
$config = file_get_contents("./config.json");

// Sets input form
$form = '<form method="post" action="'.$_SERVER["PHP_SELF"].'">Long URL: <input style="width: 250px;" type=text name="urlinput"></input><br><input style="display: none;" type="submit" id="submit" name="submit" value="Submit"> </form><button class="style-5" onclick="document.'."getElementById('submit').".'click()">Submit</button><br>'.file_get_contents('scriptandstyles.html');

$urlinput = "";

// Prevents XSS attacks.
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Automatically redirect to https if not on https already
// Changable in the config under 'autohttpsredirect. Default is true
if($config["autohttpsredirect"] == true) {
  $https = isset($_SERVER['HTTPS']) ? true : false; // Checks if client is using htttps
  if($https == false) {
    // Sets the domain and folder(s)
    // Changeable in config under 'domain' and 'folder'.
    // Defaults are 'example.com' and 'url' (eg. to access this page you go to http://example.com/url)
    // 'folder' can be set to '' (blank) if you want to use the root folder
    $domain = $config['domain'];
    $folder = $config['folder'];
    header("refresh:0;url=https://$domain/$folder?c=".$code); // Redirect
  }
 }

// Automatically adds 'http://' to the start of urls if not present (eg. 'google.com' becomes 'http://google.com').
// Changeable in config.json under 'autoaddhttp'. Default is true
if($config['autoaddhttp'] == true) {
	if(mb_substr($urlinput, 0, 8) == "https://" or mb_substr($urlinput, 0, 7) == "http://") {
		$urlinput = 'http://'.$urlinput;
	}
}

$json = file_get_contents('./list.json'); // Gets the list of base64 urls
$jsonarray = isset($json) ? json_decode($json, true) : ''; // Decodes it into an array

// Gets the submitted url from the form
$urlinput = isset($_POST["urlinput"]) ? test_input($_POST["urlinput"]) : '';

// Encode the url into base64
$base64 = base64_encode($urlinput);

// Gets the code argument from url (eg. if this code is in root directory of localhost: 'localhost/index.php?c=hello' would set $code to 'hello')
$code = isset($_GET['c']) ? test_input($_GET['c']) : '';

// Generates the 5 character long code that when used in the 'c' argument will redirect you
$first5base64 = mb_substr($base64, strlen($urlinput)-5, 5);

// Tests if the 5 digit code exists in the json array, and if it does, then $alreadyexists1 would be set to a base64 string.
$alreadyexists1 = isset($jsonarray[$first5base64]) ? $jsonarray[$first5base64] : '';

// Tests if the base64 version of the inputted url matches anything in the database
$alreadyexists = $base64 == $alreadyexists1 ? "true" : "false";


// Set up the variable
$jsonwrite = array();

// If the base64 is not empty, and it doesn't already exist in the database, then add it to the database
if($base64 != "") {
  if($alreadyexists == "false") {
    $jsonarray[$first5base64] = $base64;
  } 
}

// Adds updated version to database
file_put_contents('./list.json', json_encode($jsonarray));

// Html
echo '<html>';

// If the 'c' argument is not empty
if($code != null) {
  // Sets the html
  $redirectMessage = file_get_contents("countdownscript.html").'<div id="countdown"></div><br>If it '."doesn't".' work, click <a href="'.base64_decode($jsonarray[$code]).'">here</a>.';
  // Redirect to https if not already done
  if($https == false) {
    header("refresh:0;url=https://".$domain."/short?c=".$code);
  } else {
    // Redirect to target url after getting it and decoding it
    header("refresh:4;url=".base64_decode($jsonarray[$code]));
  }
  // Displays a countdown and a redirect message
  echo $redirectMessage;
} else {
  // If 'c' is not set, display the main form
  echo $form; // Display form
  if($first5base64 != null) { // If code is made but not in url, display the link to copy it
  echo '<br><form><label>Your link is </label><input type="text" class="link" style="width: 250px;" id="link" value="https://'.$domain.'/'.$folder.'?c='.$first5base64.'"></input></form><button class="style-5" onclick=copyTextToClipboard("https://'.$domain.'/'.$folder.'?c='.$first5base64.'");>Copy it!</button>';
  }
}

// Close html
echo '</html>';
?>