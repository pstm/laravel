<?php

class Menu {

    /**
     * Selected menu link class
     * @var string
     */
    public static $menu_link_selected = ' menu-selected';

    /**
     * Selected parent wrapper class
     * @var string
     */
    public static $menu_item_selected = ' item-selected';

    /**
     * Selected child item class
     * @var string
     */
    public static $child_item_selected = ' child-selected';

    /**
     * Adds a class to the item that has child items.
     * @var string
     */
    public static $has_child = ' has-child';

    /**
     * Apply the {$menu_link_selected} css class to each menu level.
     *
     * @param string $item
     * @return string
     */
    public static function set_selected($item) {

        if(strpos(Muli::get_route_name(), $item) === 0) {
            return static::$menu_link_selected;
        }

    }

    /**
     * Apply the CSS class to the appropriate context
     * to the selected anchors' parent wrapper.
     *
     * @param string $item
     * @param mixed $value
     * @param int $max_depth
     * @param int $depth
     * @return string
     */
    public static function set_css_class($item, $value, $max_depth, $depth) {

        $css_class = '';

        // if current item has child items, add class {$has_child}.
        if(is_array($value) && $max_depth > $depth) {
            $css_class .= static::$has_child;
        }

        // if current item, add class {$menu_item_selected}.
        if (Muli::get_route_name() == $item) {
            $css_class .= static::$menu_item_selected;
        }

        // if current item has child items, add class {$has_child}
        // and if current level is a parent add class {$child_item_selected}.
        elseif(is_array($value) && $max_depth > $depth && strpos(Muli::get_route_name(), $item) === 0) {
            $css_class .= static::$child_item_selected;
        }

        // if current level is the wrapper add class {$menu_item_selected}.
        elseif(strpos(Muli::get_route_name(), $item) === 0) {
            $css_class .= static::$menu_item_selected;
        }

        return !empty($css_class) ?' class="'. ltrim($css_class, ' ') .'"' : '';

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

        $attr = (!$is_sub) ? ' class="'. $classes .'"' : ' class="submenu-list level'. $current_depth .'"';
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

            $url = '/' . Muli::get_lang() . '/';

            if(isset($forward)) {
                $url =  $url . $forward;
            } else {
                if($id == 'switch') {
                    $url = Muli::generate_switch_link();
                } else {
                    $url = $url . __('route.' . $id);
                }
            }

            $menu .= "<li". static::set_css_class($id, $val, $max_depth, $depth) .">";
            $menu .= "<a class='menu-link". static::set_selected($id) . ($id == "switch" ? " menu-switch" : "") ."' href=". $url .">";
            $menu .= __('title.' . $id);
            $menu .= "</a>";
            $menu .= $sub;
            $menu .= "</li>\n";

            // cookie data to pass to js.
            // $menu .= ($id == 'switch' ? ' data-cookie="lang" data-lang="'. Muli::get_lang(true) .'"' : '');

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