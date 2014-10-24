<?php

/*
=====================================================
 NavEE Item
-----------------------------------------------------
 http://www.intoeetive.com/
-----------------------------------------------------
 Copyright (c) 2014 Yuri Salimovskiy
=====================================================
 This software is intended for usage with
 ExpressionEngine CMS, version 2.0 or higher
=====================================================
 File: ft.navee_item.php
-----------------------------------------------------
 Purpose: Custom field to select NavEE menu item
=====================================================
*/



if ( ! defined('BASEPATH'))
{
    exit('Invalid file request');
}


class Navee_item_ft extends EE_Fieldtype {
	
	var $info = array(
		'name'		=> 'NavEE Item',
		'version'	=> '1.0'
	);
    
	
	// --------------------------------------------------------------------
	
    
    function display_settings($data)
    {
        if (!isset($data['navigation_id'])) $data['navigation_id']='';
        
        $menus = array();    
        $q = $this->EE->db->select("navigation_id, nav_name")
            ->from('navee_navs')
            ->get();
        foreach ($q->result() as $obj)
        {
            $menus[$obj->navigation_id] = $obj->nav_name;
        }
        
        $this->EE->table->add_row(
            lang('navigation_group', 'latitude'),
            form_dropdown('navigation_id', $menus, $data['navigation_id'])
        );
    }
    
	/**
	 * Display Field on Publish
	 *
	 * @access	public
	 * @param	existing data
	 * @return	field html
	 *
	 */
	function display_field($data)
	{
		if (!isset($this->settings['navigation_id'])) $this->settings['navigation_id']='';
        
        $links = array();
        $links[''] = '';        
        $q = $this->EE->db->select("navee_id, text")
            ->from('navee')
            ->where('navigation_id', $this->settings['navigation_id'])
            ->order_by('text', 'asc')
            ->get();
        foreach ($q->result() as $obj)
        {
            $links[$obj->navee_id] = $obj->text;
        }
        
        $name = (isset($this->cell_name)) ? $this->cell_name : $this->field_name;

		$input = form_dropdown($name, $links, $data);
		
		return $input;
        
	}
	
	// --------------------------------------------------------------------
		
	/**
	 * Replace tag
	 *
	 * @access	public
	 * @param	field contents
	 * @return	replacement text
	 *
	 */
	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
        return $this->replace_link($data, $params, $tagdata);
	}
    
 

    function replace_text($data, $params = array(), $tagdata = FALSE)
	{
        if (empty($data)) return;
        
        $q = $this->EE->db->query("SELECT text FROM exp_navee WHERE navee_id='$data'");
        if ($q->num_rows>0)
        {
            return $q->row('text');
        }

        return;
	}
    
    function replace_link($data, $params = array(), $tagdata = FALSE)
	{
        if (empty($data)) return;
        
        $q = $this->EE->db->query("SELECT link FROM exp_navee WHERE navee_id='$data'");
        if ($q->num_rows>0)
        {
            return $q->row('link');
        }

        return;
	}
	
	

    
    

    function save($data)
	{
		return $data;
	}
    
    function save_settings($data) {
        return array('navigation_id' => $this->EE->input->post('navigation_id'));
    }
    
    
   	// ------------------------
	// P&T MATRIX SUPPORT
	// ------------------------
	
	/**
	 * Display Matrix field
	 */
	function display_cell($data) {
		return $this->display_field($data);
    }
	
    function display_cell_settings($data)
	{
	   return array();  
    }
    
    function save_cell_settings($data) {
		return $this->save_settings($data);
	}
    
	function save_cell($data)
	{
		return $this->save($data);
	}
    
	// --------------------------------------------------------------------
	
	/**
	 * Install Fieldtype
	 *
	 * @access	public
	 * @return	default global settings
	 *
	 */
	function install()
	{
		return array();
	}
	

}

/* End of file ft.google_maps.php */
/* Location: ./system/expressionengine/third_party/google_maps/ft.google_maps.php */