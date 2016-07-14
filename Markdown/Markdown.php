<?php
# Copyright (C) 2012-2016 Frank Bültge
# 
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

require_once( config_get( 'class_path' ) . 'MantisFormattingPlugin.class.php' );

class MarkdownPlugin extends MantisFormattingPlugin {

	/**
	 * A method that populates the plugin information and minimum requirements.
	 * 
	 * @return  void
	 */
	public function register() {
		
		$this->name        = plugin_lang_get( 'title' );
		$this->description = plugin_lang_get( 'description' );
		$this->page        = 'config';
		$this->version     = '1.1.3';
		$this->requires    = array(
			'MantisCore'           => '1.2.0, < 1.3.0',
			'MantisCoreFormatting' => '1.0a',
		);
		$this->author  = 'Frank Bültge';
		$this->contact = 'frank@bueltge.de';
		$this->url     = 'http://bueltge.de';
	}
	
	/**
	 * Set options on Core Formattting plugin
	 * 
	 * @return  boolean
	 */
	public function install() {
		
		helper_ensure_confirmed( 
			plugin_lang_get( 'install_message' ), lang_get( 'plugin_install' )
		);
		
        // check for the new core setting
		if ( 1 == config_get( 'plugin_MantisCoreFormatting_process_text' ) )
			config_set( 'plugin_MantisCoreFormatting_process_text', OFF );
        // check for old string, if is active, then change
		if ( 1 == config_get( 'plugin_format_process_text' ) )
			config_set( 'plugin_format_process_text', OFF );
		
		return TRUE;
	}
	
	/**
	 * Default plugin configuration.
	 * 
	 * @return  array default settings
	 */
	public function config() {
		
		return array(
			'process_markdown_text'          => ON,
			'process_markdown_email'         => OFF,
			'process_markdown_rss'           => OFF,
			'process_markdown_extra'         => OFF,
			'process_markdown_extended'      => OFF,
			'process_markdown_view_php'      => ON,
			'process_markdown_html_decode'   => OFF,
			'process_markdown_bbcode_filter' => OFF
		);
	}
	
	/**
	 * Filter string and fomrat with markdown function
	 * 
	 * @param  string  $p_string    Unformatted text
	 * @param   boolean $p_multiline Multiline text
	 * @return  string  $p_string
	 */
	public function string_process_markdown( $p_string, $p_multiline = TRUE ) {
		
		if ( 1 == plugin_config_get( 'process_markdown_extended' ) ) {
			
			// Kudos to
			// @see https://github.com/kierate/php-markdown-extra-extended
			require_once( dirname(__FILE__) . '/inc/markdown_extended.php' );
		} else if ( 1 == plugin_config_get( 'process_markdown_extra' ) ) {
			
			// Kudos to Michel Fortin
			// @see http://michelf.com/projects/php-markdown/
			require_once( dirname(__FILE__) . '/inc/markdown-extra.php' );
		} else {
			
			require_once( dirname(__FILE__) . '/inc/markdown.php' );
		}
		
		if ( 1 == plugin_config_get( 'process_markdown_extended' ) )
			$g_plugin_markdown_object = new MarkdownExtraExtended_Parser();
		else
			$g_plugin_markdown_object = new Markdown_Parser();  
		
		$t_change_quotes = FALSE;
		if ( ini_get_bool( 'magic_quotes_sybase' ) ) {
			$t_change_quotes = TRUE;
			ini_set( 'magic_quotes_sybase', FALSE );
		}
		
		// exclude, if bbcode inside string 
		if ( 1 == plugin_config_get( 'process_markdown_bbcode_filter' ) ) {
			if ( ! preg_match( '/\[*\]([^\[]*)\[/', $p_string, $matches ) )
				$p_string = $g_plugin_markdown_object->transform( $p_string, $p_multiline = TRUE );
		} else {
			$p_string = $g_plugin_markdown_object->transform( $p_string, $p_multiline = TRUE );
		}
		
		// Convert special HTML entities from Markdown-Function back to characters
		if ( 1 == plugin_config_get( 'process_markdown_html_decode' ) ) {
			$p_string = preg_replace_callback(
				'#(<code.*?>)(.*?)(</code>)#imsu',
				create_function(
					'$i',
					'return $i[1] . htmlspecialchars_decode( $i[2] ) . $i[3];'
				),
				$p_string
			);
		}
		
		if ( $t_change_quotes )
			ini_set( 'magic_quotes_sybase', TRUE );
		
		return $p_string;
	}
	
	/**
	 * Checks if a value from array exists in an array
	 * 
	 * @param  Mixed  The searched value.
	 * @param  Array  The array.
	 * @return Bool   Returns TRUE if needle is found in the array, FALSE otherwise.
	 */
	public function array_in_array( $needle, $haystack ) {
		
		// Make sure $needle is an array for foreach
		if ( ! is_array( $needle ) )
			$needle = array( $needle );
			
		// For each value in $needle, return TRUE if in $haystack
		foreach( $needle as $pin ) {
			if ( in_array( $pin, $haystack ) )
				return TRUE;
		}
		
		// Return FALSE if none of the values from $needle are found in $haystack
		return FALSE;
	}
	
	/**
	 * Formatted text processing.
	 * 
	 * @param  string  $p_event     Event name
	 * @param  string  $p_string    Unformatted text
	 * @param  boolean $p_multiline Multiline text
	 * @return array   $p_string    Array with formatted text and multiline parameter
	 */
	public function formatted( $p_event, $p_string, $p_multiline = TRUE ) {
		
		// array for all pages, there will be check
		$pages = array(
			'view.php', 
			'bug_change_status_page.php'
		);
		
		// format url, get bt page
		$url = explode( '/', $_SERVER['PHP_SELF'] );
		
		if ( ! $this->array_in_array( $url, $pages ) && 1 == plugin_config_get( 'process_markdown_view_php' ) )
			return $p_string;
		
		if ( 1 == plugin_config_get( 'process_markdown_text' ) )
			$p_string = $this->string_process_markdown( $p_string, $p_multiline );
		
		return $p_string;
	}
	
	/**
	 * RSS text processing.
	 * 
	 * @param  string  $p_event     Event name
	 * @param  string  $p_string    Unformatted text
	 * @param  boolean $p_multiline Multiline text
	 * @return array   $p_string    Array with formatted text and multiline parameter
	 */
	public function rss( $p_event, $p_string, $multiline = TRUE ) {
		
		if ( 1 == plugin_config_get( 'process_markdown_rss' ) )
			$p_string = $this->string_process_markdown( $p_string, $multiline );
		
		return $p_string;
	}

	/**
	 * Email text processing.
	 * 
	 * @param  string  $p_event     Event name
	 * @param  string  $p_string    Unformatted text
	 * @param  boolean $p_multiline Multiline text
	 * @return array   $p_string    Array with formatted text and multiline parameter
	 */
	public function email( $p_event, $p_string, $multiline = TRUE ) {
		
		if ( 1 == plugin_config_get( 'process_markdown_email' ) )
			$p_string = $this->string_process_markdown( $p_string, $multiline );
			
		return $p_string;
	}
	
} // end class
