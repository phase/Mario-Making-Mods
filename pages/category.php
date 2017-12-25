<?php
if (!defined('BLARG')) die();

$section = $_GET['id'];

makeAnncBar();
makeCategoryListing(0, $section);

?>
