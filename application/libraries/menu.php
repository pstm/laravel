<?php

class Menu {

    /**
     * Apply the selected css class to each menu level.
     *
     * @param string $item
     * @return string
     */
    public static function set_selected($item) {

        $selected = ' menu-selected';

        if(strpos(Muli::get_route_name(), $item) === 0) {
            return $selected;
        }

    }

    /**
     * Recursive function to generate a multi level menu.
     * taken from: http://www.copterlabs.com/blog/build-menu-with-recursive-functions/
     *
     * @param  Array   $menu_array
     * @param  boolean $is_sub
     * @param  integer $max_depth
     * @param  string  $classes
     * @param  integer $current_depth
     * @return string
     */
    public static function build_menu(Array $menu_array, $is_sub=FALSE, $max_depth = 0, $classes = NULL, $current_depth = 0)
    {

        $attr = (!$is_sub) ? ' class="'. $classes .'"' : ' class="level'. $current_depth .'"';
        $menu = "<ul$attr>\n";

        foreach($menu_array as $id => $properties) {

            foreach($properties as $key => $val) {

                if($key == 'depth') {

                    if($max_depth < $val) {
                        return false;
                    }

                }

                if(is_array($val))
                {
                    $sub = static::build_menu($val, TRUE, $max_depth, $classes, $depth+1);
                }
                else
                {
                    $sub = NULL;
                    $$key = $val;
                }
            }

            if(!isset($url)) {

                $url = $id;

            } else {

                if(isset($forward)) {
                    $url = $forward;
                }

                if($id == 'switch') {
                    $url = Muli::generate_switch_link();
                } else {
                    $url = URL::home() . $url;
                }
            }

            $menu .= "<li". ((is_array($val) && $max_depth > $depth) ? ' class="with-children"' : '') .">";
            // $menu .= "<a class='menu-link". Menu::set_selected($id) ."' href=". $url .">$title</a>";
            $menu .= '<a class="menu-link menu-switch"'. Menu::set_selected($id) .' href="'. $url .'" ';
            $menu .= ($id == 'switch' ? ' data-cookie="lang" data-lang="'. Muli::get_lang(true) .'"' : '');
            $menu .= '>'. $title .'</a>';
            $menu .= $sub;
            $menu .= "</li>\n";

            unset($url, $title, $sub, $forward);

        }

        return $menu . "</ul>\n";
    }

    /**
     * HTML helper that will generate a menu.
     *
     * Accepts the following:
     * type: first level of the sitemap array.
     * ie: 'primary' or 'secondary'
     *
     * is_sub: boolean value that determines if the current
     * level depth is more than 0.
     *
     * max_depth: int that sets the maximun level
     * at which the build_menu function stops.
     *
     * classes: string of the css class we give
     * to the root level of the menu container
     *
     * @param  string $type
     * @param  bool   $is_sub
     * @param  int    $max_depth
     * @param  string $classes
     * @return string
     */
    public static function generate_menu($type, $is_sub, $max_depth, $classes) {

        $menu = Sitemap::items();
        return static::build_menu($menu[$type], $is_sub, $max_depth, $classes);

    }

    /**
     * HTML helper that will generate a sidebar menu.
     *
     * Checks to see if their is a 'sub' array key,
     * if so, set that as the starting array menu item.
     * TODO: make this easily configurable.
     * ie: set a starting depth.
     *
     * Accepts the following:
     * type: first level of the sitemap array.
     * ie: 'primary' or 'secondary'
     *
     * is_sub: boolean value that determines if the current
     * level depth is more than 0.
     *
     * max_depth: int that sets the maximun level
     * at which the build_menu function stops.
     *
     * classes: string of the css class we give
     * to the root level of the menu container
     *
     * @param  string $type
     * @param  bool   $is_sub
     * @param  int    $max_depth
     * @param  string $classes
     * @return string
     */
    public static function generate_sidebar_menu($type, $is_sub, $max_depth, $classes) {

        $menu = Sitemap::items();
        $parts = explode('_', Muli::get_route_name());

        if(isset($menu[$type][$parts[0]]['sub'])) {
            return static::build_menu($menu[$type][$parts[0]]['sub'], $is_sub, $max_depth, $classes);
        }

    }

}