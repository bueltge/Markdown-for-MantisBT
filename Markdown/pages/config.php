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

html_page_top1( plugin_lang_get( 'title' ) );
html_page_top2();

print_manage_menu();
?>

<br/>
<form action="<?php echo plugin_page( 'config_edit' ); ?>" method="post">
<table align="center" class="width50" cellspacing="1">
	
	<tr>
		<td class="form-title" colspan="3">
			<?php echo plugin_lang_get( 'title' ) . ': ' . plugin_lang_get( 'config' )?>
		</td>
	</tr>
	
	<tr <?php echo helper_alternate_class(); ?>>
		<td class="category" width="60%">
			<?php echo plugin_lang_get( 'process_text' )?>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_text" value="1" <?php echo ( ON == plugin_config_get( 'process_markdown_text' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'enabled' )?></label>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_text" value="0" <?php echo ( OFF == plugin_config_get( 'process_markdown_text' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'disabled' )?></label>
		</td>
	</tr>
	
	<tr <?php echo helper_alternate_class(); ?>>
		<td class="category" width="60%">
			<?php echo plugin_lang_get( 'process_rss' )?>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_rss" value="1" <?php echo ( ON == plugin_config_get( 'process_markdown_rss' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'enabled' )?></label>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_rss" value="0" <?php echo ( OFF == plugin_config_get( 'process_markdown_rss' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'disabled' )?></label>
		</td>
	</tr>
	
	<tr <?php echo helper_alternate_class(); ?>>
		<td class="category" width="60%">
			<?php echo plugin_lang_get( 'process_email' )?>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_email" value="1" <?php echo ( ON == plugin_config_get( 'process_markdown_email' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'enabled' )?></label>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_email" value="0" <?php echo ( OFF == plugin_config_get( 'process_markdown_email' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'disabled' )?></label>
		</td>
	</tr>
	
	<tr <?php echo helper_alternate_class(); ?>>
		<td class="category" width="60%">
			<?php echo plugin_lang_get( 'process_extended' ); ?>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_extended" value="1" <?php echo ( ON == plugin_config_get( 'process_markdown_extended' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'enabled' )?></label>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_extended" value="0" <?php echo ( OFF == plugin_config_get( 'process_markdown_extended' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'disabled' )?></label>
		</td>
	</tr>
	<?php
	if ( ON == plugin_config_get( 'process_markdown_extended' ) )
		$disabled = ' disabled="disabled"';
	else
	 	$disabled = '';
	?>
	<tr <?php echo helper_alternate_class(); ?>>
		<td class="category" width="60%">
			<?php echo plugin_lang_get( 'process_extra' ); ?>
		</td>
		<td class="center" width="20%">
			<label><input <?php echo $disabled; ?> type="radio" name="process_markdown_extra" value="1" <?php echo ( ON == plugin_config_get( 'process_markdown_extra' ) || ON == plugin_config_get( 'process_markdown_extended' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'enabled' )?></label>
		</td>
		<td class="center" width="20%">
			<label><input <?php echo $disabled; ?> type="radio" name="process_markdown_extra" value="0" <?php echo ( OFF == plugin_config_get( 'process_markdown_extra' ) && OFF == plugin_config_get( 'process_markdown_extended' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'disabled' )?></label>
		</td>
	</tr>
	
	<tr <?php echo helper_alternate_class(); ?>>
		<td class="category" width="60%">
			<?php echo plugin_lang_get( 'process_view_php' ); ?>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_view_php" value="1" <?php echo ( ON == plugin_config_get( 'process_markdown_view_php' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'enabled' )?></label>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_view_php" value="0" <?php echo ( OFF == plugin_config_get( 'process_markdown_view_php' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'disabled' )?></label>
		</td>
	</tr>
	
	<tr <?php echo helper_alternate_class(); ?>>
		<td class="category" width="60%">
			<?php echo plugin_lang_get( 'process_html_decode' ); ?>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_html_decode" value="1" <?php echo ( ON == plugin_config_get( 'process_markdown_html_decode' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'enabled' )?></label>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_html_decode" value="0" <?php echo ( OFF == plugin_config_get( 'process_markdown_html_decode' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'disabled' )?></label>
		</td>
	</tr>
	
	<tr <?php echo helper_alternate_class(); ?>>
		<td class="category" width="60%">
			<?php echo plugin_lang_get( 'process_bbcode_filter' ); ?>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_bbcode_filter" value="1" <?php echo ( ON == plugin_config_get( 'process_markdown_bbcode_filter' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'enabled' )?></label>
		</td>
		<td class="center" width="20%">
			<label><input type="radio" name="process_markdown_bbcode_filter" value="0" <?php echo ( OFF == plugin_config_get( 'process_markdown_bbcode_filter' ) ) ? 'checked="checked" ' : ''?>/>
				<?php echo plugin_lang_get( 'disabled' )?></label>
		</td>
	
	<tr>
		<td class="center" colspan="3">
			<input type="submit" class="button" value="<?php echo lang_get( 'change_configuration' )?>" />
		</td>
	</tr>

</table>
<form>

<?php
html_page_bottom1( );