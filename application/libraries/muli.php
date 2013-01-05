<?php

class Muli {

	/**
	 * Menu level delimiter. '-', '.', '~' etc...
	 * Must be changed inside the application/language/{lang}/route.php
	 * and in application/models/sitemap.php if a different delimiter
	 * is applied.
	 *
	 * @var string
	 */
	protected static $delimiter = '_';

	/**
	 * Get the length of the current url with the localized segment.
	 * http://www.example.com/en/
	 *
	 * @return int
	 */
	public static function get_localized_url_length() {
		return strlen(URL::home());
	}

	/**
	 * Get the length of the full url.
	 *
	 * @return int
	 */
	public static function get_full_url_length() {
		return strlen(URL::full());
	}

	/**
	 * Return the url without the base url and localized segment.
	 * http://www.example.com/en/section1/subsection1/
	 * -> "section1/subsection1"
	 *
	 * The returned value is equal to the value of the current route name (aka $key inside the route array)
	 * found in "application/language/{lang}/route.php".
	 * 'section1_subsection1' => 'section1/subsection1'
	 *
	 * @return string
	 */
	public static function get_remaining_url_data() {
		return substr(URL::full(), static::get_localized_url_length());
	}

	/**
	 * Get the current application language
	 *
	 * @param  boolean $other_lang
	 * @return string
	 */
	public static function get_lang($other_lang = false) {

		// get default application language
		$default_language = Config::get('application.language');

		// verify that the languages array isn't empty and has more than one language in it.
		// is so, assign the array to a variable.
		if(is_array(Config::get('application.languages')) && count(Config::get('application.languages')) > 1) {
			$languages = Config::get('application.languages');
		}

		if(URI::segment(1)) {
			$language_segment = URI::segment(1);
		} else {
			$language_segment = Config::get('application.language');
		}

		if(!$other_lang) {
			// if no param is passed to the function, return current lang
			return $language_segment;

		} else {
			// if '$other_lang=true' parameter is passed, check default lang and depending
			// on the available languages in the array, return the other language
			if(($key = array_search($language_segment, $languages)) !== false) {

	            // remove current language from the array
	            unset($languages[$key]);

	            // sort the array to re-index the key
	            sort($languages);
	        }

	        // since it's re-indexed, the first element will always be at index 0
	        return $languages[0];

		}

	}

	/**
	 * Return the current route name.
	 *
	 * 1. get url (without lang) and trim the trailing '/'.
	 * 2. search inside the route array -> "application/language/{lang}/route.php".
	 * 3. return matching route name.
	 *
	 * @return string
	 */
	public static function get_route_name() {
		return array_search( rtrim( static::get_remaining_url_data(),'/'), __('route')->get(static::get_lang() ) );
	}

	/**
	 * Get the route array for the other available language
	 * -> "application/language/{other_lang}/route.php"
	 *
	 * @return string
	 */
	public static function get_switch_route() {
		return __('route')->get(static::get_lang(true));
	}

	/**
	 * Generate the switch url/html for the corresponding route
	 *
	 * @param  boolean $html generate html anchor tag
	 * @return string
	 */
	public static function generate_switch_link($html = false) {

		// set other language route array to variable
		$routeSwitch = static::get_switch_route();

		// get other lang value and match the current route name to the corresponding key inside the route array
		$routeLink = URL::base() . '/' . static::get_lang(true) . '/' . $routeSwitch[static::get_route_name()];

		if($html) {
			// if parameter 'html' is set to true, generate the markup
			return '<a class="menu-link menu-switch" href="' . $routeLink . '">'. __('title.switch') .'</a>';
		} else {
			// return the localized url
			return $routeLink;
		}

	}

	/**
	 * Get the corresponding route url.
	 *
	 * The $route_key parameter is equal to the matching $key
	 * found in -> "application/language/{lang}/route.php".
	 *
	 * @param  string $route_key
	 * @return string
	 */
	public static function get_route_link($route_key) {
		return URL::home() . __('route.' . $route_key);
	}

	/**
	 * Get the current page title.
	 *
	 * You have the choice to return simply the current h1 title
	 * by passing true to the function or full page title with
	 * each individual level.
	 *
	 * @param  boolean $is_h1
	 * @return string
	 */
	public static function get_page_title($is_h1 = false) {

		$parts = explode(static::$delimiter, static::get_route_name());

		if(!$is_h1 && count($parts) > 1) {

			// assign current route (path) 'static::get_route_name()' to a variable
			$title = static::build_title(static::get_route_name());

			return $title;

		} else {
			return __('title.' . static::get_route_name());
		}

	}

	/**
	 * Build title from the current route name.
	 * The route name is equal tp a key inside the title array.
	 * found in -> "application/language/{lang}/title.php".
	 *
	 * @param  string $route_name
	 * @return array
	 */
	public static function build_title($route_name) {

		// Split into an array.
		$parts = explode(static::$delimiter, $route_name);

		// Get the count from the current route.
		$count = count($parts);

		// Array that will hold each title level.
		$title_array = array();

		// String that will be used to break down into parts.
		$string_to_break = $route_name;

		// Add the newly edited string to $title_array and repeat untill the string
		// has no more delimiter meaning you've reached the root
		// route name: section1_subsection1_subsubsection1
		// => array(
		//      'section1_subsection1_subsubsection1',
		//      'section1_subsection1',
		//      'section1'
		//    )
		while ($count > 0) {

			// If current route, keep the value intact and add to the $title_array.
			if($count === count($parts)) {

				array_push($title_array, $route_name);

			} else {

				// Send the current $route_name to be broken down for each level
				// which is delimited by the following character -> '_'
				$string_to_break = static::breakdown_string($string_to_break);

				// Once each $route_name has been looped and broken down
				// (from the right), push to $title_array.
				array_push($title_array, $string_to_break);
			}

			$count--;
		}

		return static::format_page_title($title_array);
	}

	/**
	 * break down variable from the right with the following delimiter $delimiter.
	 *
	 * @param  string $route_name
	 * @return string
	 */
	public static function breakdown_string($route_name) {

		$delimiter_position = strrpos($route_name, static::$delimiter);
		$new_string = substr($route_name, 0, $delimiter_position);

		return $new_string;

	}

	/**
	 * Loop through each route name to find the corresponding title value
	 * and build the page title
	 *
	 * @param  array $title_array
	 * @return string
	 */
	public static function format_page_title($title_array) {

		$title = '';

		foreach ($title_array as $route_name) {
			$title .= __('title.' . $route_name) . ' - ';
		}

		return rtrim($title, ' - ');

	}

	public static function set_body_class() {
		$bodyClass = str_replace('_', '-', static::get_route_name());
		return ' class="'. $bodyClass .'"';
	}

	/**
	 * Render the appropriate content for the supplied parameter.
	 *
	 * @param  string $language_content
	 * @return view
	 */
	public static function render_content($language_content){
		return View::make('content/'. static::get_lang() .'/' . $language_content);
	}

	/**
	 * Merge both sitemap levels together so that it contains all of the sitemap items.
	 *
	 * TODO: Add array parameter to let the user select which
	 * level is to be added. (depending on the custom level values set in the sitemap).
	 * i.e. sitemap_routes(array('primary', 'secondary')).
	 *
	 * @return array
	 */
	public static function sitemap_routes() {

		$sitemap = Sitemap::items();

		// To be modified for optimization.
		$type = static::array_flatten($sitemap['type'], TRUE);
		$routes = $type + $sitemap['secondary'];

		return $routes;

	}

	/**
	 * Converts the sitemap model array into a flatten array.
	 * Prepares the structure of the data to loop through inside
	 * the routes.php file.
	 *
	 * @param  array  $array
	 * @param  boolean $preserve
	 * @param  array   $result
	 * @return array
	 */
	public static function array_flatten($array, $preserve = FALSE, $result = array()){

        foreach($array as $key => $value){

            if (is_array($value)){

                foreach($value as $subkey => $subvalue){
                    if (is_array($subvalue)) {
                    	$tmp = $subvalue; unset($value[$subkey]);
                    }
                }

                if ($preserve) {
                	$result[$key] = $value;
                }
                else {
                	$result[] = $value;
                }
            }

            $result = isset($tmp) ? static::array_flatten($tmp, $preserve, $result) : $result;

        }

        return $result;
    }

    /**
     * Set the current layout for the specified page.
     * Settable in the sitemap model. $key = 'layout'.
     *
     * @param  array $value
     * @return string
     */
    public static function set_layout($value){

    	$default = 'master';
    	return array_key_exists('layout', $value) ? $value['layout'] : $default;

    }

}