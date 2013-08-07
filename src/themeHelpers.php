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
	return Config::get('core::wardrobe.theme_dir').'/'.$file;
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
	return wardrobe_path(Config::get('core::wardrobe.theme').'/'.$file);
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

use dflydev\markdown\MarkdownExtraParser;

if ( ! function_exists('md'))
{
	function md($str)
	{
		$markdownParser = new MarkdownExtraParser();

		// Parse the loaded source.
		return $markdownParser->transformMarkdown($str);
	}
}

