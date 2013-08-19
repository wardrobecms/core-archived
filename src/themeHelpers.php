<?php

/**
 * Site Title
 *
 * Helper that allows you to easily get the site title
 *
 * @return string
 */
function site_title()
{
	return Config::get('core::wardrobe.title');
}

/**
 * Wardrobe Path
 *
 * Helper that allows you to easily get a theme path inside the views.
 * Example: @extends(theme_path('layout'))
 *
 * @param string $file - The file to load
 * @return string
 */
function wardrobe_path($file = null)
{
	return asset('/packages/wardrobe/core/'.$file);
}

/**
 * Theme Path
 *
 * Helper that allows you to easily get a theme path inside the views.
 * Example: @extends(theme_path('layout'))
 *
 * @param string $file - The file to load
 * @return string
 */
function theme_path($file = null)
{
	return asset('/'.Config::get('core::wardrobe.theme_dir').'/'.Config::get('core::wardrobe.theme').'/'.$file);
}

/**
 * Theme View Path
 *
 * Helper that allows you to easily get a theme view path inside the views.
 * Example: @extends(theme_path('layout'))
 *
 * @param string $file - The file to load
 * @return string
 */
function theme_view($file = null)
{
	return Config::get('core::wardrobe.theme').'.'.$file;
}

use \Michelf\MarkdownExtra;

if ( ! function_exists('md'))
{
	function md($str)
	{
		return MarkdownExtra::defaultTransform($str);
	}
}

function wardrobe_url($link)
{
	if($link[0] == '/') {
    	$link = substr($link, 1);
	}
	if (route('wardrobe.index', null, false) !== '/') {
		return route('wardrobe.index')."/{$link}";
	} else {
		return url($link);
	}
}
