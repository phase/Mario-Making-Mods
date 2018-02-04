<?php
if (!defined('BLARG')) die();

die('Doesn\'t work for now. Move along.');

$section = $_GET['id'];

makeAnncBar();
makeCategoryListing(0, $section);

?>
