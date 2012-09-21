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

auth_reauthenticate( );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

$f_process_markdown_text          = gpc_get_int( 'process_markdown_text', ON );
$f_process_markdown_rss           = gpc_get_int( 'process_markdown_rss', ON );
$f_process_markdown_email         = gpc_get_int( 'process_markdown_email', ON );
$f_process_markdown_extra         = gpc_get_int( 'process_markdown_extra', OFF );
$f_process_markdown_bbcode_filter = gpc_get_int( 'process_markdown_bbcode_filter', ON );

if ( plugin_config_get( 'process_markdown_text' ) != $f_process_markdown_text )
	plugin_config_set( 'process_markdown_text', $f_process_markdown_text );

if ( plugin_config_get( 'process_markdown_rss' ) != $f_process_markdown_rss )
	plugin_config_set( 'process_markdown_rss', $f_process_markdown_rss );

if ( plugin_config_get( 'process_markdown_email' ) != $f_process_markdown_email )
	plugin_config_set( 'process_markdown_email', $f_process_markdown_email );

if ( plugin_config_get( 'process_markdown_extra' ) != $f_process_markdown_extra )
	plugin_config_set( 'process_markdown_extra', $f_process_markdown_extra );

if ( plugin_config_get( 'process_markdown_bbcode_filter' ) != $f_process_markdown_bbcode_filter )
	plugin_config_set( 'process_markdown_bbcode_filter', $f_process_markdown_bbcode_filter );

print_successful_redirect( plugin_page( 'config', TRUE ) );