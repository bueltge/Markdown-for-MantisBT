<?php
# Copyright (C) 2012 Frank Bültge
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
		$this->version     = '1.0.0';
		$this->requires    = array(
			'MantisCore'           => '1.2.0',
			'MantisCoreFormatting' => '1.0a'
		);
		$this->author  = 'Frank B&uuml;ltge';
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
		
		config_set( 'plugin_format_process_text', OFF );
		config_set( 'plugin_format_process_urls', OFF );
		
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
		
		// Kudos to Michel Fortin
		// http://michelf.com/projects/php-markdown/
		if ( 1 == plugin_config_get( 'process_markdown_extra' ) )
			require_once( dirname(__FILE__) . '/inc/markdown-extra.php' );
		else
			require_once( dirname(__FILE__) . '/inc/markdown.php' );
		
		if ( ! class_exists( 'Markdown_Parser' ) )
			return $p_string; 
		
		$g_plugin_markdown_object = new Markdown_Parser();
		
		$t_change_quotes = FALSE;
		if ( ini_get_bool( 'magic_quotes_sybase' ) ) {
			$t_change_quotes = TRUE;
			ini_set( 'magic_quotes_sybase', FALSE );
		}
		
		// exclude, if bbcode inside string 
		if ( 1 == plugin_config_get( 'process_markdown_bbcode_filter' ) ) {
			if ( ! preg_match( '/\[*\]([^\[]*)\[/', $p_string, $matches ) )
				$p_string = $g_plugin_markdown_object->transform( $p_string, $multiline );
		} else {
			$p_string = $g_plugin_markdown_object->transform( $p_string, $multiline );
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
	 * Formatted text processing.
	 * 
	 * @param  string  $p_event     Event name
	 * @param  string  $p_string    Unformatted text
	 * @param  boolean $p_multiline Multiline text
	 * @return array   $p_string    Array with formatted text and multiline parameter
	 */
	public function formatted( $p_event, $p_string, $p_multiline = TRUE ) {
		
		if ( FALSE === strpos( $_SERVER['PHP_SELF'], '/view.php' ) && 1 == plugin_config_get( 'process_markdown_view_php' ) )
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