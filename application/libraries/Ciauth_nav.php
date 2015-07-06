<?php

class Ciauth_nav {

    public $db_fields;
    public $max_pages = 1;
    public $has_children;

    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }

    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';   
        $output .= $indent . '<li>';
        $output .= '<a href="' . $item->anchor . '">' . $item->name . '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        $output .= "</li>\n";
    }
    
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
        if (!$element)
            return;
        $id_field = $this->db_fields['id'];
        $id = $element->$id_field;
        //display this element
        $this->has_children = !empty($children_elements[$id]);
        if (isset($args[0]) && is_array($args[0])) {
            $args[0]['has_children'] = $this->has_children; // Backwards compatibility.
        }
        $cb_args = array_merge(array(&$output, $element, $depth), $args);
        call_user_func_array(array($this, 'start_el'), $cb_args);
        // descend only when the depth is right and there are childrens for this element
        if (($max_depth == 0 || $max_depth > $depth + 1 ) && isset($children_elements[$id])) {
            foreach ($children_elements[$id] as $child) {
                if (!isset($newlevel)) {
                    $newlevel = true;
                   
                    $cb_args = array_merge(array(&$output, $depth), $args);
                    call_user_func_array(array($this, 'start_lvl'), $cb_args);
                }
                $this->display_element($child, $children_elements, $max_depth, $depth + 1, $args, $output);
            }
            unset($children_elements[$id]);
        }
        if (isset($newlevel) && $newlevel) {
            //end the child delimiter
            $cb_args = array_merge(array(&$output, $depth), $args);
            call_user_func_array(array($this, 'end_lvl'), $cb_args);
        }
        //end this element
        $cb_args = array_merge(array(&$output, $element, $depth), $args);
        call_user_func_array(array($this, 'end_el'), $cb_args);
    }

    public function walk($elements, $max_depth) {
        $args = array_slice(func_get_args(), 2);
        $output = '';
        if ($max_depth < -1) //invalid parameter
            return $output;
        if (empty($elements)) //nothing to walk
            return $output;
        $parent_field = $this->db_fields['parent'];
        
        if (-1 == $max_depth) {
            $empty_array = array();
            foreach ($elements as $e)
                $this->display_element($e, $empty_array, 1, 0, $args, $output);
            return $output;
        }
 
        $top_level_elements = array();
        $children_elements = array();
        foreach ($elements as $e) {
            if (0 == $e->$parent_field)
                $top_level_elements[] = $e;
            else
                $children_elements[$e->$parent_field][] = $e;
        }

        if (empty($top_level_elements)) {
            $first = array_slice($elements, 0, 1);
            $root = $first[0];
            $top_level_elements = array();
            $children_elements = array();
            foreach ($elements as $e) {
                if ($root->$parent_field == $e->$parent_field)
                    $top_level_elements[] = $e;
                else
                    $children_elements[$e->$parent_field][] = $e;
            }
        }
        foreach ($top_level_elements as $e)
            $this->display_element($e, $children_elements, $max_depth, 0, $args, $output);
        
        if (( $max_depth == 0 ) && count($children_elements) > 0) {
            $empty_array = array();
            foreach ($children_elements as $orphans)
                foreach ($orphans as $op)
                    $this->display_element($op, $empty_array, 1, 0, $args, $output);
        }
        return $output;
    }

    public function unset_children($e, &$children_elements) {
        if (!$e || !$children_elements)
            return;
        $id_field = $this->db_fields['id'];
        $id = $e->$id_field;
        if (!empty($children_elements[$id]) && is_array($children_elements[$id]))
            foreach ((array) $children_elements[$id] as $child)
                $this->unset_children($child, $children_elements);
        if (isset($children_elements[$id]))
            unset($children_elements[$id]);
    }

}