<?php
function removeSpecialCharacter($str) {
	$str = str_replace('\\', '\\\\', $str);
	$str = str_replace('\'', '\\\'', $str);
	return $str;
}

function getPost($key) {
	$value = '';
	if(isset($_POST[$key])) {
		$value = $_POST[$key];
	}

	return removeSpecialCharacter($value);
}

function getGet($key) {
	$value = '';
	if(isset($_GET[$key])) {
		$value = $_GET[$key];
	}

	return removeSpecialCharacter($value);
}

function getShoppingBagIcon($classes = '', $additionalAttributes = '') {
    $defaultClasses = 'fas fa-shopping-bag';
    $allClasses = trim($defaultClasses . ' ' . $classes);
    
    return '<i class="' . $allClasses . '" ' . $additionalAttributes . '></i>';
}
