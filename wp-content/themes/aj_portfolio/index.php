<?php
/*
Template Name: Default page
*/

// Get global data
$context = Timber::get_context();
$templates = array('views/index.twig');

// Render page
Timber::render($templates, $context);