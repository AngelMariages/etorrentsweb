<?php

class WPZOOM_Admin_Settings_Fields {
    public $first = true;

    public function preheader($args) {
        extract($args);

        if (!$this->first) {
            $out.= "</div>";
        }
        $this->first = false;
        $stitle = 'wpz_' . substr(md5($name), 0, 8);
        $out.= "<div class=\"sub\" id=\"$stitle\">";

        $out.= '<div class="zoom-sub-header">';
        $out.= "<h4>$name</h4>";

        if (isset($desc)) {
            if (is_array($desc)) {
                foreach ($desc as $row) {
                    $out.= "<p>$row</p>";
                }
            } else {
                $out.= "<p class=\"description\">$desc</p>";
            }
        }

        $out.='</div>';

        return $out;
    }

    public function startsub($args) {
        extract($args);

        $out.= '<fieldset>';
        $out.= "<legend>$name</legend>";
        $out.= '<div class="sub_right">';
        $out.= '</div>';

        return $out;
    }

    public function endsub($args) {
        $out = '</fieldset>';

        return $out;
    }

    public function color($args) {
        extract($args);

        $val = option::get($id) ? option::get($id) : '';
        $out.= "<label>$name</label>";
        $out.= '<div class="colorSelector"><div></div></div><input name="'.$id.'" id="'.$id.'" class="txt input-text input-colourpicker" type="text" value="'.$val.'"></input>';
        $out.= "<p class=\"description\">$desc</p>";

        return $out;
    }

    public function button($args) {
        extract($args);

        // Defaults
        $type   = isset($type) ? $type : 'button';
        $class  = isset($class) ? $class : 'button-primary';
        $id     = isset($id) ? $id : 'wpz_button_' . rand(10, 100);

        $out.= "<button type=\"$type\" class=\"$class\" id=\"$id\">$name</button>";
        $out.= "<p class=\"description\">$desc</p>";

        return $out;
    }

    public function paragraph($args) {
        extract($args);

        $out.= "<div class=\"checkbox\"><label>$name</label>";
        $out.= "<p class=\"description\">$desc</p></div>";

        return $out;
    }

    public function checkbox($args) {
        extract($args);

        $out.= '<div class="checkbox">';
        $out.= '<input type="checkbox" class="checkbox" name="'.$id.'" id="'.$id.'" ';
        if ($value == "on") {
            $out.= ' checked="checked"';
        } elseif (!$value && $std == "on") {
            $out.= ' checked="checked"';
        }
        $out.= " />";
        $out.= "<label for=\"$id\">$name</label>";
        $out.= "<p class=\"description\">$desc</p>";
        $out.= "</div>";

        return $out;
    }

    public function select($args) {
        extract($args);

        $out.= "<label>$name</label>";
        $out.= "<select name=\"$id\" id=\"$id\">";

        foreach ($options as $option) {
            $out.= '<option' . selected($option, $value, false) . '>' . $option;
            $out.= '</option>';
        }

        $out.= "</select>";
        $out.= "<p class=\"description\">$desc</p>";

        return $out;
    }

    public function dropdown_categories($args){
        extract($args);

        $out= "<label>$name</label>";
        $args['selected'] = option::get($id);
        $args['name'] = $id;
        $args['echo'] = 0;

        $out.= wp_dropdown_categories($args);
        $out.= "<p class=\"description\">$desc</p>";
        return $out;
    }

    public function select_category($args) {
        extract($args);

        unset($catids,$catnames);
        $categoriesParents = ui::getCategories();
        if (count($categoriesParents) > 0) {
            foreach ( $categoriesParents as $key => $value ) {
                $catids[] = $key;
                $catnames[] = $value;
            }
        }
        $out.= "<label>$name</label>";
        $out.= "<select name=\"$id\">";

        $out.= "<option value=\"0\"";
        $out.= (option::get($id) == 0) ? ' selected="selected"' : '';
        $out.= '> - select a category -';
        $out.= "</option>";

        foreach ($catids as $key => $val) {
            $out.= "<option value=\"$val\"";
            $out.= (option::get($id) == $val) ? ' selected="selected"' : '';
            $out.= '>' . $catnames[$key];
            $out.= "</option>";
        }
        $out.= "</select>";
        $out.= "<p class=\"description\">$desc</p>";

        return $out;
    }

    public function select_category_multi($args) {
        extract($args);

         unset($catids,$catnames);
        $categoriesParents = ui::getCategories();
        if (count($categoriesParents) > 0) {
            foreach ( $categoriesParents as $key => $value ) {
                $catids[] = $key;
                $catnames[] = $value;
            }
        }
        $activeoptions = is_array(option::get($id)) ? option::get($id) : array();
        $out.= "<label>$name</label>";
        $out.= "<select id=\"s_$id\" multiple=\"true\" name=\"" . $id . "[]\" style=\"height: 150px\">";

        $out.= "<option value=\"0\"";
        $out.= (in_array(0, $activeoptions)) ? ' selected="selected"' : '';
        $out.= '> - select a category -';
        $out.= "</option>";

        if (count($catids) > 0) {
            foreach ($catids as $key => $val) {
                $out.= "<option value=\"$val\"";
                if (in_array($val, $activeoptions)) {
                    $out.= ' selected="selected"';
                }
                $out.= ">" . $catnames[$key];
                $out.= '</option>';
            }
        }
        $out.= "</select>";
        $out.= "<p class=\"description\">$desc</p>";

        return $out;
    }

    public function select_page($args) {
        extract($args);

        $pages = get_pages();

        $out.= "<label>$name</label>";
        $out.= "<select name=\"$id\">";

        $out.= '<option value="0"';
        $out.= (option::get($id) == 0) ? ' selected="selected"' : '';
        $out.= '> - select a page -';
        $out.= "</option>";

        foreach ($pages as $page) {
            $out.= "<option value=\"{$page->ID}\"";
            $out.= (option::get($id) == $page->ID) ? ' selected="selected"' : '';
            $out.= '>' . get_the_title($page->ID);
            $out.= "</option>";
        }

        $out.= "</select>";
        $out.= "<p class=\"description\">$desc</p>";

        return $out;
    }

    public function select_tag_multi($args) {
        extract($args);

        unset($catids,$catnames);
        $categoriesParents = get_categories('taxonomy=post_tag');;

        $catids = array();
        $catnames = array();

        if (count($categoriesParents) > 0) {
            foreach ( $categoriesParents as $cat ) {
                $catids[] = $cat->term_id;
                $catnames[] = $cat->category_nicename;
            }
        }
        $activeoptions = is_array(option::get($id)) ? option::get($id) : array();
        $out.= "<label>$name</label>";
        $out.= "<select id=\"s_$id\" multiple=\"true\" name=\"" . $id . "[]\" style=\"height: 150px\">";

        $out.= "<option value=\"0\"";
        $out.= (in_array(0, $activeoptions)) ? ' selected="selected"' : '';
        $out.= '> - select a tag -';
        $out.= "</option>";

        if (count($catids) > 0) {
            foreach ($catids as $key => $val) {
                $out.= "<option value=\"$val\"";
                if (in_array($val, $activeoptions)) {
                    $out.= ' selected="selected"';
                }
                $out.= ">" . $catnames[$key];
                $out.= '</option>';
            }
        }
        $out.= "</select>";
        $out.= "<p class=\"description\">$desc</p>";

        return $out;
    }

    public function select_layout($args) {
        extract($args);

        $out.= "<label class=\"layout_label\">$name</label>";

        foreach ($options as $key => $val) {
            $out.= "<input id=\"$id--$key\" type=\"radio\" class=\"RadioClass\" name=\"$id\" value=\"$key\"";
            if (option::get($id) == $key) {
                $out .= ' checked';
            }
            $out.= ' />';
            $out.= "<label for=\"$id--$key\" class=\"RadioLabelClass";
            if (option::get($id) == $key) {
                $out .= ' RadioSelected';
            }
            $out.= "\">";
            $out.= "<img src=\"".WPZOOM::$wpzoomPath."/assets/images/layout-$key.png\" alt=\"\" title=\"$val\" class=\"layout-select\" /></label>";
        }
        $out.= "<p class=\"description\">$desc</p>";

        return $out;
    }

    public function text($args) {
        extract($args);

        $out.= "<label for=\"$id\">$name</label>";
        $out.= "<input name=\"$id\" id=\"$id\" type=\"$type\" value=\"" . esc_attr($value) . '" />';
        $out.= "<p class=\"description\">$desc</p>";

        return $out;
    }

    public function textarea($args) {
        extract($args);

        $value = apply_filters('wpzoom_field_' . $id, $value);

        $out.= "<label>$name</label>";
        $out.= "<textarea id=\"$id\" name=\"$id\" type=\"$type\" colls=\"\" rows=\"\">$value</textarea>";
        $out.= "<p class=\"description\">$desc</p>";

        return $out;
    }

    public function typography($args) {
        extract($args);

        $selected_value = is_array(option::get($id)) ? option::get($id) : array();

        $out.= '<div class="zoom-typography">';

        $out.= "<label>$name</label>";

        /* fonts */
        $font_families = ui::recognized_font_families($id);
        $google_font_families = ui::recognized_google_webfonts_families($id);
        $font_family = isset($selected_value['font-family']) ? $selected_value['font-family'] : '';

        $out.= '<select name="' . $id . '[font-family]" id="' . $id . '-family" class="zoom-typography-family">';
            $out.= '<optgroup label="' . __('Web Safe Fonts', 'wpzoom') . '">';
            $out.= '<option value="">font-family</option>';
            foreach ($font_families as $key => $value) {
                $out.= '<option value="' . esc_attr($key) . '" ' . selected($font_family, $key, false) . '>' . esc_attr($value) . '</option>';
            }
            $out.= '</optgroup>';

            $out.= '<optgroup label="' . __('Google Web Fonts', 'wpzoom') . '">';
            foreach ($google_font_families as $value) {
                if (isset($value['separator'])) {
                    $out.= '<option value="" disabled="disabled">' . $value['separator'] . '</option>';

                    continue;
                }

                $key = str_replace(' ', '-', strtolower($value['name']));
                $out.= '<option value="' . esc_attr($key) . '" ' . selected($font_family, $key, false) . '>' . esc_attr($value['name']) . '</option>';
            }
            $out.= '</optgroup>';

        $out.= '</select>';

        /* style */
        $font_styles = ui::recognized_font_styles($id);
        $font_style = isset($selected_value['font-style']) ? $selected_value['font-style'] : '';

        $out.= '<select name="' . $id . '[font-style]" id="' . $id . '-style" class="zoom-typography-style">';
            $out.= '<option value="">font-style</option>';
            foreach ($font_styles as $key => $value) {
                $out.= '<option value="' . esc_attr($key) . '" ' . selected($font_style, $key, false) . '>' . esc_attr($value) . '</option>';
            }
        $out.= '</select>';

        /* sizing */
        $font_size = isset($selected_value['font-size']) ? $selected_value['font-size'] : '';

        $out.= '<select name="' . $id . '[font-size]" id="' . $id . '-size" class="zoom-typography-size">';
            $out.= '<option value="">size</option>';
            for ($i = 8; $i <= 72; $i++) {
                $size = $i . 'px';
                $out.= '<option value="' . esc_attr($size) . '" ' . selected($font_size, $size, false) . '>' . esc_attr($size) . '</option>';
            }
        $out.= '</select>';

        /* color */
        $font_color = isset($selected_value['font-color']) ? $selected_value['font-color'] : '';
        $out.= '<div class="colorSelector"><div></div></div><input name="'.$id.'[font-color]" id="'.$id.'-color" class="txt input-text input-colourpicker zoom-typography-color" type="text" value="'.$font_color.'"></input>';

        $out.= "<p class=\"description\">$desc</p>";

        $out.= '</div><!-- /div.zoom-typography -->';

        return $out;
    }

    public function upload($args) {
        extract($args);

        $out.= "<label>$name</label>";

        if ($id === 'misc_favicon' && function_exists('has_site_icon')) {
            $out.= sprintf(__( '<p>This option has been deprecated in favor of the Site Icon setting in WordPress core. Please visit %s to configure your site icon.</p>', 'wpzoom' ),
                sprintf('<a href="%1$s">%2$s</a>',
                    esc_url(admin_url('options-general.php')),
                    __('General Settings', 'wpzoom')
                )
            );
        } else {
            $out.= WPZOOM_Medialib_Uploader::action($id, $value, $desc, 0, $name);
        }

        return $out;
    }

    public function separator() {
        return '<div class="sep">&nbsp;</div>';
    }

    public function cleaner() {
        return '<div class="cleaner">&nbsp;</div>';
    }

    public function notice($args) {
        extract($args);

        $out .= "<div class=\"zoom-notice\">$desc</div>";

        return $out;
    }

    public function page_content($args) {
        extract($args);

        ob_start();
        include_once($path);
        $page_content = ob_get_clean();

        $out .= "<div id=\"$id\">";
        $out .= $page_content;
        $out .= "</div>";

        return $out;
    }
}
