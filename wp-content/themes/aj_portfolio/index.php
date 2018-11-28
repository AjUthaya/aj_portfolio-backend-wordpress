<?php
/*
Template Name: Default page
*/

// Get global data
$context = Timber::get_context();
$templates = array('containers/index.twig');

// Render page
Timber::render($templates, $context);