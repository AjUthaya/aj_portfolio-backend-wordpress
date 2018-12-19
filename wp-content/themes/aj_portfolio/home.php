<?php
/*
Template Name: Frontpage
*/

// Get global data
$context = Timber::get_context();
$templates = array('views/home.twig');

// Render page
Timber::render($templates, $context);