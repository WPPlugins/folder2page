<?php
/*
Plugin Name: Folder2Page
Plugin URI: http://www.beakersoft.co.uk/Folder2Page
Description: Allows you to build a page in wordpress based on image in a folder
Version: 1.0
Author: Luke Niland
Author URI: http://www.beakersoft.co.uk

Copyright 2009  Luke Niland  (email : luke@beakersoft.co.uk)

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

add_action('admin_init', 'folder2oage_options_init' );
add_action('admin_menu', 'folder2page_options_add_page');
add_action('wp_print_styles', 'folder2page_stylesheets');

### Function: Enqueue PageNavi Stylesheets
function folder2page_stylesheets() {
	
	$style = WP_PLUGIN_URL . '/Folder2Page/Folder2Page.css';
	$location = WP_PLUGIN_DIR . '/Folder2Page/Folder2Page.css';
	if ( file_exists($location) ) {
	        wp_register_style('template', $style);
	        wp_enqueue_style( 'template');
	}
	}

// Init plugin options to white list our options
function folder2oage_options_init(){
	register_setting( 'folder2page_options_options', 'folder2page_options', 'folder2page_options_validate' );
}

// Add menu page
function folder2page_options_add_page() {
	add_options_page('Folder2Page Options', 'Folder2Page', 'manage_options', 'folder2page_options', 'folder2page_do_page');
}

// Draw the menu page itself
function folder2page_do_page() {
	?>

	<div class="wrap">
		<h2>Folder2Page Options</h2>
		<form method="post" action="options.php">
			<?php settings_fields('folder2page_options_options'); ?>
			<?php $options = get_option('folder2page_options'); ?>
			<table class="form-table">
				<tr valign="top"><th scope="row">Path to Images</th>
					<td><input type="text" size="70" name="folder2page_options[folder2page_path1]" value="<?php echo $options['folder2page_path1']; ?>" /></td>					
				</tr>				
				<tr valign="top"><th scope="row">URL to Image Folder</th>
					<td><input type="text" size="70" name="folder2page_options[folder2page_url1]" value="<?php echo $options['folder2page_url1']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row">Image Preview Width Size(pixels)</th>
					<td><input type="text" size="10" name="folder2page_options[folder2page_size1]" value="<?php echo $options['folder2page_size1']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row">Link thumbnail to original image?</th>
					<td><input type="checkbox" size="10" name="folder2page_options[folder2page_allowlink1]" value="1" <?php checked('1', $options['folder2page_allowlink1']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row">Number of Images Per Row</th>
					<td><input type="text" size="10" name="folder2page_options[folder2page_picsperrow1]" value="<?php echo $options['folder2page_picsperrow1']; ?>" /></td>
				</tr>
			</table>
											
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Settings') ?>" />
			</p>
		</form>
	</div>

	
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function folder2page_options_validate($input) {
		
	// Say our second option must be safe text with no HTML tags
	$input['folder2page_path1'] =  wp_filter_nohtml_kses($input['folder2page_path1']);
	//$input['folder2page_size1'] =  is_numeric($input['folder2page_size1']);
		
	return $input;
}


### Write out the table with image
function Display_Images() {

//get the array containing our options
$options = get_option('folder2page_options');

//What files to we want to show, eventually get this from option. Always make uppercase
$show_files = array(
				'.JPG',
				'.PNG',
				'.GIF'
			);

//set the start dir to the users option
$startdir = $options['folder2page_path1'];
$starturl = $options['folder2page_url1'];
$imagesize = $options['folder2page_size1'];
$picsperrow = $options['folder2page_picsperrow1'];
$LinkToOrig = $options['folder2page_allowlink1']; 	//1 means its True

$leadon = $startdir;
if($leadon=='.') $leadon = '';

//make sure there is a training / on the dir, and the url
if((substr($leadon, -1, 1)!='/') && $leadon!='') $leadon = $leadon . '/';
if((substr($starturl, -1, 1)!='/') && $starturl!='') $starturl = $starturl . '/';

//make sure there is a preceding . on the dir
if((substr($leadon, 0, 1)!='.') && $leadon!='') $leadon = '.' . $leadon;
$startdir = $leadon;

$opendir = $leadon;
if(!$leadon) $opendir = '.';

if(!$leadon) $opendir = '.';
if(!file_exists($opendir)) {		//THIS IS FLAGGING AS NO EXIST
	$opendir = '.';
	$leadon = $startdir;
}

//Clear the file cache and turn of errors
clearstatcache();
//error_reporting(0);

if ($handle = opendir($opendir)) {

	//write out the start of the table
	echo "<table summary=\"Folder2Page Image Results\" class=\"Folder2PageTab\" cellspacing=\"0\">";
	echo "<tr>";
	
	//set the initial counter for the rows we have
	$rowCnt=0;
	
    while (false !== ($file = readdir($handle))) {
	
		//dont want to show if its a dir handle
        if ($file != "." && $file != "..") {
						
			//loop through the allowed list, if we match then output the file
			for($hi=0;$hi<sizeof($show_files);$hi++) {
				if(strpos(strtoupper($file), $show_files[$hi])!==false) {
				
					//we have a good file, work out its url so we can display it
					$URLToImage = $starturl . $file;
					
					if ($LinkToOrig==1) 
						$outputImage = "<a href=\"" . $URLToImage . "\" target=\"_blank\"><img src=\"" . $URLToImage . "\" width=\"" . $imagesize . "\" title=\"" . $file . "\" /></a>";
					else
						$outputImage = "<img src=\"" . $URLToImage . "\" width=\"" . $imagesize . "\" title=\"" . $file . "\" />";
					
					
					//do we need a close/open table row?
					if ($rowCnt == $picsperrow) {
						echo "</tr><tr>";
						$rowCnt=0;
					}
					
					//now output the row 
					echo "<td class=\"Folder2PageData\">" . $outputImage . "</td>";
				
					$rowCnt++;
					//echo $starturl . "$file\n"."<br />";
				}
			}
		
        }
    }
	
	//close the table
	echo "</tr>";
	echo "</table>";
	
    closedir($handle);
}

/* http://php.net/manual/en/function.readdir.php */

}

?>
