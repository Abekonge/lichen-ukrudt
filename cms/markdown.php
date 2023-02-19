<?php

require_once "vendor/php-markdown-lib-9.1/Michelf/MarkdownExtra.inc.php";

use Michelf\MarkdownExtra;

function markdown_to_html($file, $generate_headline_ids = true)
{
	$source = "";

	while (!feof($file)) {
		$source = $source . fgets($file);
	}

	$html = MarkdownExtra::defaultTransform($source);

	preg_match_all('|<!-- TITLE:(.*) -->|', $html, $out);
	$title = trim(implode($out[1]));

	return array("html" => $html, "title" => $title);
}
