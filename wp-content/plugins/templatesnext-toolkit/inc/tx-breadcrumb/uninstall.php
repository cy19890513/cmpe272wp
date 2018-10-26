<?php
/**
 * TX Breadcrumb - uninstall script
 *
 * uninstall script based on WordPress Uninstall Plugin API
 * 
 * 
 * Because bctx_admin->uninstall() does not work with WPMU, 
 * an uninstaller class has been written, that encapsulates 
 * the uninstall logic and calls bctx_admin->uninstall() 
 * when applicable.
 * 
 * @see http://codex.wordpress.org/Migrating_Plugins_and_Themes_to_2.7#Uninstall_Plugin_API
 * @see http://trac.mu.wordpress.org/ticket/967
 *
 * this uninstall.php file was executed multiple times because 
 * tx breadcrumb (until 3.3) constsisted of two plugins:
 *
 *	1.) tx_breadcrumb_class.php / Core
 *  2.) tx_breadcrumb_admin.php / Adminstration Interface
 *  
 * @author Tom Klingenberg
 */
/*  
	Copyright 2010-2018  John Havlik  (email : john.havlik@mtekk.us)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//Ensure the uninstall.php file was only called by WordPress and not directly
if(!defined('WP_UNINSTALL_PLUGIN'))
{
	//First catches the Apache users
	header("HTTP/1.0 404 Not Found");
	//This should catch FastCGI users
	header("Status: 404 Not Found");
	die();
}
require_once(dirname(__FILE__) . '/includes/class.mtekk_adminkit_uninstaller.php');

/**
 * TX Breadcrumb uninstaller class
 * 
 * @author Tom Klingenberg
 */
class bctx_uninstaller extends mtekk_adminKit_uninstaller
{
	protected $unique_prefix = 'bctx';
	protected $plugin_basename = null;
	
	public function __construct()
	{
		$this->plugin_basename = plugin_basename('/tx-breadcrumb.php');
		parent::__construct();
	}
	/**
	 * Options uninstallation function for legacy
	 */
	private function uninstall_legacy()
	{
		delete_option($this->unique_prefix . '_options');
		delete_option($this->unique_prefix . '_options_bk');
		delete_option($this->unique_prefix . '_version');
		delete_site_option($this->unique_prefix . '_options');
		delete_site_option($this->unique_prefix . '_options_bk');
		delete_site_option($this->unique_prefix . '_version');
	}
	/**
	 * uninstall tx breadcrumb admin plugin
	 * 
	 * @return bool
	 */
	private function uninstall_options()
	{
		if(version_compare(phpversion(), '5.3.0', '<'))
		{
			return $this->uninstall_legacy();
		}
		//Grab our global tx_breadcrumb object
		global $tx_breadcrumb;
		//Load dependencies if applicable
		if(!class_exists('tx_breadcrumb'))
		{
			require_once($this->_get_plugin_path());
		}
		//Initalize $tx_breadcrumb so we can use it
		$bctx_breadcrumb_trail = new bctx_breadcrumb_trail();
		//Let's make an instance of our object takes care of everything
		$tx_breadcrumb = new tx_breadcrumb($bctx_breadcrumb_trail);
		//Uninstall
		return $tx_breadcrumb->uninstall();
	}	
	
	/**
	 * uninstall method
	 * 
	 * @return bool wether or not uninstall did run successfull.
	 */
	public function uninstall()
	{
		//Only bother to do things if we have something in the database
		if($this->is_installed())
		{
			return $this->uninstall_options();
		}	
	}
	
} /// class bctx_uninstaller

/*
 * main
 */
new bctx_uninstaller();