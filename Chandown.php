<?php

/**
 * Slimdown - A very basic regex-based Markdown parser.
 * Author: Johnny Broadway <johnny@johnnybroadway.com>
 * Website: https://gist.github.com/jbroadway/2836900
 * License: MIT
 */
class Slimdown {
	public static $rules = array (
    //Markdown
		// Links
		'/(?<!!)\[([^\[]+)\]\(([^\)]+)\)/' => '<a href=\'\2\'>\1</a>',
		// Bold
		'/(\*\*|__)(.*?)\1/' => '<strong>\2</strong>',
		// Emphasis
		'/(\*|_)(.*?)\1/' => '<em>\2</em>',
		// Del
		'/\~\~(.*?)\~\~/' => '<del>\1</del>',
		// Quotes
		'/\:\"(.*?)\"\:/' => '<q>\1</q>',
		// Inline code
		'/`(.*?)`/' => '<code>\1</code>',
		// Images
		'/(?:!\[([^\[]+)\]\(([^\)]+)\))/' => '<br><a href=\'\2\' target=\'\_blank\'><img src=\'\2\' alt=\'\1\' loading=\'lazy\'></a>',
		'/==(.*?)==/' => '<mark>\1</mark>',

		//BBCode
		// Links
		'~\[url\]((?:ftp|https?)://.*?)\[/url\]~s' => '<a href="$1">$1</a>',
		// Bold
		'~\[b\](.*?)\[/b\]~s' => '<b>$1</b>',
		// Emphasis
		'~\[i\](.*?)\[/i\]~s' => '<em>$1</em>',
		// Quotes
		'~\[quote\](.*?)\[/quote\]~s' => '<pre>$1</'.'pre>',
		// Inline code
		'~\[code\](.*?)\[/code\]~s' => '<code>\1</code>',
		// Images
		'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s' => '<img src="$1" alt="" />',
		// Underline
		'~\[u\](.*?)\[/u\]~s' => '<span style="text-decoration:underline">$1</span>',
		// Font size
		'~\[size=(.*?)\](.*?)\[/size\]~s' => '<span style="font-size:$1px">$2</span>',
		// Font color
		'~\[color=(.*?)\](.*?)\[/color\]~s' => '<span style="color:$1">$2</span>',
		// YouTube
		'~\[youtube\](.*?)\[/youtube\]~s' => '<iframe width="457" height="257" src="https://www.youtube.com/embed/$1" frameborder="0" allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" allowfullscreen></iframe>',
		// YouTube Time
		'~\[youtube time=(.*?)\](.*?)\[/youtube\]~s' => '<iframe width="457" height="257" src="https://www.youtube.com/embed/$1?t=$2" frameborder="0" allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" allowfullscreen></iframe>',

		// Chan things
		// To another boards
		'/\&gt\;\&gt\;\&gt\;\/(\w+)\//' => '<a href="?board=$1" style="color:red">&gt;&gt;&gt;/$1/</a>',
		// To another posts
		'/\&gt\;\&gt\;(\w+)\#(\w+)?/' => '<a href="?t=$1#$2" style="color:red">&gt;&gt;$1#$2</a>',
		// Greentext
    '/\&gt\;(.*?)\<br\>/' => '<span style="color:green">&gt;$1</span><br>',
		'/\<br\>\&gt\;(.*?)/' => '<br><span style="color:green">&gt;$1</span>',
		// Pinktext
    '/\&lt\;(.*?)\<br\>/' => '<span style="color:pink">&lt;$1</span><br>',
		'/\<br\>\&lt\;(.*?)/' => '<br><span style="color:pink">&lt;$1</span>',
	);

	/**
	 * Add a rule.
	 */
	public static function add_rule ($regex, $replacement) {
		self::$rules[$regex] = $replacement;
	}

	/**
	 * Render some Markdown into HTML.
	 */
	public static function render ($text) {
		$text = "\n" . $text . "\n";
		foreach (self::$rules as $regex => $replacement) {
			if (is_callable ( $replacement)) {
				$text = preg_replace_callback ($regex, $replacement, $text);
			} else {
				$text = preg_replace ($regex, $replacement, $text);
			}
		}
		return trim ($text);
	}
}
