<?php

$haschanged = 0;
$needstobehttps = false;

function change($tochange, $changed) {
	if($changed == 0) {
		$tochange = 'http://'.$tochange;
		$changed = 1;
	} else {
		$tochange = $tochange;
	}
	$haschanged = 1;
	return $tochange;
}

$urlinput = $argv[1];
$usesother = $argv[2];

if(mb_substr($urlinput, 0, 8) == "https://") {
	$urlinput = str_replace("https://", "", $urlinput);
	$needstobehttps = true;
}
if(mb_substr($urlinput, 0, 7) != "http://") {
	if($haschanged == 0) {
		$urlinput = change($urlinput, $haschanged);
	}
}
if($needstobehttps == true) {
	$urlinput = str_replace("http://", "https://", $urlinput);
}
if($usesother == true) {
	$urlinput = mb_substr($urlinput, 7);
}
echo "\nResult is $urlinput";