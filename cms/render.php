<?php
require "./gemtext.php";
require "./markdown.php";

// get the body content
$_src = null;
$path = null;
$ext = null;
$_content = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$_src = fopen("php://input", "r");
	$path = $_SERVER['PATH_INFO'];
	$ext = pathinfo($path, PATHINFO_EXTENSION);
} else if (isset($_SERVER['REDIRECT_URL'])) {
	$path = $_SERVER['REDIRECT_URL'];
	$_src = fopen(".." . $path, "r") or die("File not found: " . $path);
	$ext = pathinfo(".." . $path, PATHINFO_EXTENSION);
	header("Last-Modified: " . date("r", filemtime(".." . $path)));
} else {
	$path = ".." . $_SERVER['PATH_INFO'];
	$_src = fopen($path, "r") or die("File not found: " . $path);
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	header("Last-Modified: " . date("r", filemtime($path)));
}

if ($ext == "gmi") {
	$_content = gemtext_to_html($_src);
} else if ($ext == "md") {
	$_content = markdown_to_html($_src);
}
fclose($_src);

// Get page body
$body = $_content['body'];

// When header or footer is being edited / displayed, don't display in layout
$displayLayout = !strpos($path, 'header.md') && !strpos($path, 'footer.md');

// Get header and footer
$header = null;
$footer = null;

if (file_exists('../header.md') && $displayLayout) {
	$header_src = fopen('../header.md', 'r');
	$header = markdown_to_html($header_src)['body'];
	fclose($header_src);
}
if (file_exists('../footer.md') && $displayLayout) {
	$footer_src = fopen('../footer.md', 'r');
	$footer = markdown_to_html($footer_src)['body'];
	fclose($footer_src);
}

// Get metadata
$title = $_content['title'];

include "../theme/layout.php";
