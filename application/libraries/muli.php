<?php

class Muli {

	/**
	 * get the length of the current url with the localized segment
	 *
	 * @return int
	 */
	public static function get_localized_url_length() {
		return strlen(URL::home());
	}

	/**
	 * get the length of the complete url
	 * @return int
	 */
	public static function get_full_url_length() {
		return strlen(URL::full());
	}

	/**
	 * get the the rest of the urls' segments which
	 * will become the key name string
	 *
	 * @return string
	 */
	public static function get_url_params() {
		return substr(URL::full(), Muli::get_localized_url_length());
	}

	/**
	 * get the current application language
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

			// TODO: verify if there is a language cookie,
			// if so, set the default application language with the cookie value.

			// set it too the default application language (when no cooie value is set)
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
	 * return the current route name
	 *
	 * 1. get url (without lang) and trim the trailing '/'
	 * 2. search inside the route array and find a match
	 * 3. return matching route name
	 *
	 * @return string
	 */
	public static function get_route_name() {
		return array_search( rtrim( Muli::get_url_params(),'/'), __('route')->get(Muli::get_lang() ) );
	}

	/**
	 * get the route array for the other available language
	 *
	 * @return string
	 */
	public static function get_switch_route() {
		return __('route')->get(Muli::get_lang(true));
	}

	/**
	 * generate the switch url/html for the corresponding route
	 *
	 * @param  boolean $html generate html anchor tag
	 * @return string
	 */
	public static function generate_switch_link($html = false) {

		// set other language route array to variable
		$routeSwitch = Muli::get_switch_route();

		// get other lang value and match the current route name to the corresponding key inside the route array
		$routeLink = URL::base() . '/' . Muli::get_lang(true) . '/' . $routeSwitch[Muli::get_route_name()];

		if($html) {
			// if parameter 'html' is set to true, generate the markup
			return '<a class="menu-link menu-switch" href="' . $routeLink . '">'. __('title.switch') .'</a>';
		} else {
			// return the localized url
			return $routeLink;
		}

	}

	/**
	 * gets the url value from the route array.
	 *
	 * @param  string $link
	 * @return string
	 */
	public static function get_route_link($link) {
		return URL::home() . __('route.' . $link);
	}

	/**
	 * gets the title value from the title array.
	 *
	 * @param  string $title
	 * @return string
	 */
	public static function get_title_link($title) {
		return __('sitemap.' . $title);
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

		$parts = explode('_', Muli::get_route_name());

		if(!$is_h1 && count($parts) > 1) {

			// assign current route (path) 'Muli::get_route_name()' to a variable
			$title = Muli::build_title(Muli::get_route_name());

			return $title;

		} else {
			return __('title.' . Muli::get_route_name());
		}

	}

	/**
	 *
	 *
	 * @param  string $route_name
	 * @return array
	 */
	public static function build_title($route_name) {

		// split into an array
		$parts = explode('_', $route_name);

		// get the count from the current route.
		$count = count($parts);
		$build = array();
		$string_to_break = $route_name;


		// add the newly edited string to an array and repeat untill the string
		// has no more delimiter meaning you've reached the root
		while ($count > 0) {

			if($count === count($parts)) {
				array_push($build, $route_name);
			} else {

				$string_to_break = Muli::breakdown_string($string_to_break);
				array_push($build, $string_to_break);
			}

			$count--;
		}

		return Muli::format_page_title($build);
	}

	/**
	 * break down variable from the right with the following delimiter '_'
	 *
	 * @param  string $route_name
	 * @return string
	 */
	public static function breakdown_string($route_name) {

		$delimiter_position = strrpos($route_name, "_");
		$new_string = substr($route_name, 0, $delimiter_position);

		return $new_string;

	}

	/**
	 * Loop through each route name to find the corresponding title value
	 * and build the page title
	 *
	 * @param  array $build
	 * @return string
	 */
	public static function format_page_title($build) {

		$title = '';

		foreach ($build as $route_name) {
			$title .= __('title.' . $route_name) . ' - ';
		}

		return rtrim($title, ' - ');

	}

}