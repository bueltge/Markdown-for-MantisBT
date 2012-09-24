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
		$this->version     = '0.0.1';
		$this->requires    = array(
			'MantisCore' => '1.2.0',
		);
		$this->uses = array(
			'MantisCoreFormatting' => '1.0a'
		);
		$this->author  = 'Frank B&uuml;ltge';
		$this->contact = 'frank@bueltge.de';
		$this->url     = 'http://bueltge.de';
	}
	
	public function install() {
		
	// helper_ensure_confirmed( plugin_lang_get( 'install_message' ), lang_get( 'plugin_install' ) );
	// config_set( 'plugin_MantisCoreFormatting_process_urls', OFF );
		
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
			'process_markdown_email'         => ON,
			'process_markdown_rss'           => ON,
			'process_markdown_extra'         => OFF,
			'process_markdown_bbcode_filter' => OFF
		);
	}
	
	/**
	 * Filter string and fomrat with markdown function
	 * 
	 * @param   string $p_string
	 * @return  string $p_string
	 */
	public function string_process_markdown( $p_string ) {
		
		// Kudos to Michel Fortin
		// http://michelf.com/projects/php-markdown/
		if ( ON == plugin_config_get( 'process_markdown_extra' ) )
			require_once( dirname(__FILE__) . '/inc/markdown-extra.php' );
		else
			require_once( dirname(__FILE__) . '/inc/markdown.php' );
			
		if ( ! function_exists( 'Markdown' ) )
			return $p_string; 
		
		$t_change_quotes = FALSE;
		if ( ini_get_bool( 'magic_quotes_sybase' ) ) {
			$t_change_quotes = TRUE;
			ini_set( 'magic_quotes_sybase', FALSE );
		}
		
		// exclude, if bbpress inside string 
		if ( ON == plugin_config_get( 'process_markdown_bbcode_filter' ) ) {
			if ( ! preg_match( '/\[*\]([^\[]*)\[/', $p_string, $matches ) )
				$p_string = Markdown( $p_string );
		} else {
			$p_string = Markdown( $p_string );
		}
		
		if ( $t_change_quotes )
			ini_set( 'magic_quotes_sybase', TRUE );
		
		return $p_string;
	}
	
	/**
	 * Plain text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @param  boolean Multiline text
	 * @return multi Array with formatted text and multiline paramater
	 */
	public function text( $p_event, $p_string, $p_multiline = TRUE ) {
		
		$t_string = $p_string;
		
		if ( ON == plugin_config_get( 'process_markdown_text' ) )
			$this->string_process_markdown( $t_string );
		
		return $t_string;
	}
	
	/**
	 * RSS text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @return string Formatted text
	 */
	public function rss( $p_event, $p_string ) {
		
		$t_string = $p_string;
		
		if ( ON == plugin_config_get( 'process_markdown_rss' ) )
			$t_string = $this->string_process_markdown( $t_string );
		
		return $t_string;
	}

	/**
	 * Email text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @return string Formatted text
	 */
	public function email( $p_event, $p_string ) {
		
		$t_string = $p_string;
		
		$t_string = string_strip_hrefs( $t_string );
		$t_string = string_process_bug_link( $t_string, FALSE );
		$t_string = string_process_bugnote_link( $t_string, FALSE );
		$t_string = string_process_cvs_link( $t_string, FALSE );
		
		return $t_string;
	}
	
	/**
	 * Formatted text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @param  boolean Multiline text
	 * @return multi Array with formatted text and multiline parameter
	 */
	public function formatted( $p_event, $p_string, $p_multiline = TRUE ) {
		
		$t_string = $p_string;
		
		if ( ON == plugin_config_get( 'process_markdown_text' ) )
			$t_string = $this->string_process_markdown( $t_string );
		
		return $t_string;
	}
	
} // end class