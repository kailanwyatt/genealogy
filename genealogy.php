<?php
/*
Plugin Name: Genealogy
Plugin URI: https://www.suiteplugins.com
Description: Genealogy plugin will help you map out your family relationships using WordPress.
Author: suiteplugins
Version: 1.2.2
Author URI: https://www.suiteplugins.com

Copyright 2016 SuitePlugins.  (email: info@suiteplugins.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/


/**
 * Main initiation class
 *
 * @since  NEXT
 */
final class Genealogy {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  NEXT
	 */
	const VERSION = '1.2.2';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var Genealogy
	 * @since  NEXT
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  NEXT
	 * @return Genealogy A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  NEXT
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function plugin_classes() {
		// Attach other plugin classes to the base plugin class.
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Activate the plugin
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function _activate() {
		// Make sure any rewrite functionality has been loaded.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function _deactivate() {}

	/**
	 * Init hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function init() {
		if ( $this->check_requirements() ) {
			load_plugin_textdomain( 'genealogy', false, dirname( $this->basename ) . '/languages/' );
			$this->plugin_classes();
		}
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  NEXT
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {

			// Deactivate our plugin.
			add_action( 'admin_init', array( $this, 'deactivate_me' ) );

			return false;
		}

		return true;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function deactivate_me() {
		deactivate_plugins( $this->basename );
	}

	/**
	 * Check that all plugin requirements are met
	 *
	 * @since  NEXT
	 * @return boolean True if requirements are met.
	 */
	public static function meets_requirements() {
		// Do checks for required classes / functions
		// function_exists('') & class_exists('').
		// We have met all requirements.
		return true;
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  NEXT
	 * @param string $field Field to get.
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'projects':
			case 'ajax':
				return $this->$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory
	 *
	 * @since  NEXT
	 * @param  string $filename Name of the file to be included.
	 * @return bool   Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( 'includes/class-'. $filename .'.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory
	 *
	 * @since  NEXT
	 * @param  string $path (optional) appended path.
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url
	 *
	 * @since  NEXT
	 * @param  string $path (optional) appended path.
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}
}

/**
 * Grab the Genealogy object and return it.
 * Wrapper for Genealogy::get_instance()
 *
 * @since  NEXT
 * @return Genealogy  Singleton instance of plugin class.
 */
function genealogy() {
	return Genealogy::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( genealogy(), 'hooks' ) );

register_activation_hook( __FILE__, array( genealogy(), '_activate' ) );
register_deactivation_hook( __FILE__, array( genealogy(), '_deactivate' ) );


add_action( 'init', 'genealogy_init');
add_action('admin_enqueue_scripts', 'genealogy_head',99999);
add_action('wp_print_styles', 'genealogy_head');
add_action('admin_init', 'genealogy_admin_init',9999);

function genealogy_settings_init() {
	register_setting( 'genealogy_options', 'genealogy', 'genealogy_sanitize_settings');
}
function genealogy_sanitize_settings($input) {
	return $input;
}
function genealogy_settings_link( $links, $file ) {
	$this_plugin = plugin_basename(__FILE__);
    if ( $file == $this_plugin ) {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=genealogy' ) . '">' . __('Settings', 'genealogy') . '</a>';
        array_unshift( $links, $settings_link ); // before other links
    }
    return $links;
}

function genealogy_admin() {
    add_options_page('Genealogy', 'Genealogy', 'administrator', 'genealogy', 'genealogy_admin_page');
}

function genealogy_get_settings() {
	return get_option('genealogy', array());
}

// THANKS JOOST!
function genealogy_form_table($rows) {
    $content = '<table class="form-table" width="100%">';
    foreach ($rows as $row) {
        $content .= '<tr><th valign="top" scope="row" style="width:50%">';
        if (isset($row['id']) && $row['id'] != '')
            $content .= '<label for="'.$row['id'].'" style="font-weight:bold;">'.$row['label'].':</label>';
        else
            $content .= $row['label'];
        if (isset($row['desc']) && $row['desc'] != '')
            $content .= '<br/><small>'.$row['desc'].'</small>';
        $content .= '</th><td valign="top">';
        $content .= $row['content'];
        $content .= '</td></tr>';
    }
    $content .= '</table>';
    return $content;
}

function genealogy_postbox($id, $title, $content, $padding=false) {
    ?>
        <div id="<?php echo $id; ?>" class="postbox">
            <div class="handlediv" title="Click to toggle"><br /></div>
            <h3 class="hndle"><span><?php echo $title; ?></span></h3>
            <div class="inside" <?php if($padding) { echo 'style="padding:10px; padding-top:0;"'; } ?>>
                <?php echo $content; ?>
            </div>
        </div>
    <?php
}

function genealogy_admin_page() {
		$settings = genealogy_get_settings();
		$show_relationship_thumbnails = $show_last_modified = $show_last_modified_to_admins = $genealogy_show_birth_date_of_living = $genealogy_show_credit = false;
		extract($settings);
		?>
		<div class="wrap">
		<h2><?php _e('Genealogy Settings'); ?></h2>
		<div class="postbox-container" style="width:65%;">
			<div class="metabox-holder">
				<div class="meta-box-sortables">
					<form action="options.php" method="post">
				   <?php
						wp_nonce_field('update-options');
						settings_fields('genealogy_options');

						$checked = (empty($show_relationship_thumbnails) || $show_relationship_thumbnails == 'yes') ? ' checked="checked"' : '';
						$rows[] = array(
								'id' => 'genealogy_show_relationship_thumbnails',
								'label' => __('Show thumbnails of relationships', 'genealogy'),
								'desc' => 'Show thumbnails for relatives on a single-person page',
								'content' => "<p><label for='genealogy_show_relationship_thumbnails'><input type='hidden' name='genealogy[show_relationship_thumbnails]' value='no' /><input type='checkbox' name='genealogy[show_relationship_thumbnails]' value='yes' id='genealogy_show_relationship_thumbnails' {$checked} /> Show thumbnails of siblings, children, etc. on single-person pages</label></p>"
						);

						$checkedNo = (empty($show_last_modified) || $show_last_modified == 'no') ? ' checked="checked"' : '';
						$checkedEveryone = (!empty($show_last_modified) && $show_last_modified == 'everyone') ? ' checked="checked"' : '';
						$checkedAdmins = (!empty($show_last_modified) && $show_last_modified == 'admins') ? ' checked="checked"' : '';

						$rows[] = array(
								'id' => 'genealogy_show_last_modified',
								'label' => __('Show "last modified" date to everyone', 'genealogy'),
								'desc' => 'Show everyone the last time a person\'s data was modified.',
								'content' => "<ul>
										<li><label for='genealogy_show_last_modified_no'><input type='radio' name='genealogy[show_last_modified]' value='no' id='genealogy_show_last_modified_no' {$checkedNo} /> Hide \"last modified\" date</label></li>
										<li><label for='genealogy_show_last_modified_everyone'><input type='radio' name='genealogy[show_last_modified]' value='everyone' id='genealogy_show_last_modified_everyone' {$checkedEveryone} /> Show \"last modified\" to everyone</label></li>
										<li><label for='genealogy_show_last_modified_to_admins'><input type='radio' name='genealogy[show_last_modified]' value='admins' id='genealogy_show_last_modified_to_admins' {$checkedAdmins} /> Show \"last modified\" to administrators only</label></li>
									</ul>
								"
						);

						$checkedNo = (!empty($show_birth_date) && $show_birth_date == 'no') ? ' checked="checked"' : '';
						$checkedAll = (empty($show_birth_date) || $show_birth_date == 'all') ? ' checked="checked"' : '';
						$checkedDead = (!empty($show_birth_date) && $show_birth_date == 'dead') ? ' checked="checked"' : '';

						$rows[] = array(
								'id' => 'genealogy_show_birth_date',
								'label' => __('Show birth dates', 'genealogy'),
								'desc' => 'Show the birth dates of family members',
								'content' => "<ul>
										<li><label for='genealogy_show_birth_date_all'><input type='radio' name='genealogy[show_birth_date]' value='all' id='genealogy_show_birth_date_all' {$checkedAll} /> Show birth dates for living and deceased</label></li>
										<li><label for='genealogy_show_birth_date_dead'><input type='radio' name='genealogy[show_birth_date]' value='dead' id='genealogy_show_birth_date_dead' {$checkedDead} /> Show birth dates for deceased only</label></li>
										<li><label for='genealogy_show_birth_date_no'><input type='radio' name='genealogy[show_birth_date]' value='no' id='genealogy_show_birth_date_no' {$checkedNo} /> Hide all birth dates</label></li>
									</ul>"
						);

						genealogy_postbox('genealogysettings',__('Genealogy Plugin Settings', 'genealogy'), genealogy_form_table($rows), false);

					?>


						<input type="hidden" name="page_options" value="<?php foreach($rows as $row) { $output .= $row['id'].','; } echo substr($output, 0, -1);?>" />
						<input type="hidden" name="action" value="update" />
						<p class="submit">
						<input type="submit" class="button-primary" name="save" value="<?php _e('Save Changes', 'genealogy') ?>" />
						</p>
					</form>
				</div>
			</div>
		</div>
		<div class="postbox-container" style="width:34%;">
			<div class="metabox-holder">
				<div class="meta-box-sortables">
				<?php genealogy_postbox('genealogyhelp',__('Bugs, Questions or Comments', 'genealogy'), '<p>If you have any questions, please leave feedback on the <a href="http://wordpress.org/tags/genealogy?forum_id=10">plugin support forum</a>.</p>', true);	 ?>
				</div>
			</div>
		</div>

	</div>
	<?php
	}

function genealogy_do_settings_filters() {
	$settings = genealogy_get_settings();

	if( ! empty( $settings['show_relationship_thumbnails'] ) && 'no' == $settings['show_relationship_thumbnails'] ) {
		add_filter('genealogy_show_relationship_thumbnails', 'genealogy_returnfalse');
	}

	if(!empty($settings['show_last_modified'])) {
		switch($settings['show_last_modified']) {
			case 'everyone':
				add_filter('genealogy_show_last_modified', 'genealogy_returntrue');
				break;
			case 'admins':
				add_filter('genealogy_show_last_modified_to_admins', 'genealogy_returntrue');
				break;
			case 'no':
			default:
				break;
		}
	}

	$settings['show_birth_date'] = empty($settings['show_birth_date']) ? '' : $settings['show_birth_date'];
	switch($settings['show_birth_date']) {
		case 'no':
			add_filter('genealogy_show_birth_date', 'genealogy_returnfalse');
			break;
		case 'dead':
			add_filter('genealogy_show_birth_date_of_living', 'genealogy_returnfalse');
			break;
		case 'all':
		default:
			break;
	}

}

function genealogy_init() {
	if( ! genealogy_check_compatibility(true) ) { return false; };

	define('GENEALOGY_PLUGIN_URL', plugin_dir_url(__FILE__));
	define('GENEALOGY_PLUGIN_VERSION', '1.1');

	if(apply_filters('genealogy_replace_excerpts', true)) {
		add_filter('the_excerpt', 'genealogy_excerpt');
	}

	if(is_admin()) {
		add_action('admin_menu', 'genealogy_admin');
   		add_filter('plugin_action_links', 'genealogy_settings_link', 10, 2 );
   		add_action('admin_init', 'genealogy_settings_init');
	}

	genealogy_do_settings_filters();

	$args = array(
	  'labels' => array(
	    'name' => _x( 'Family Members', 'family'),
	    'singular_name' => __( 'Family Member', 'genealogy'),
	    'new_item_name' => __( 'Family Member', 'genealogy'),
	    'add_new' => _x('Add New', 'family'),
	    'add_new_item' => __('Add New Family Member', 'genealogy'),
	    'edit_item' => __('Edit Family Member'),
	    'new_item' => __('New Family Member'),
	    'view_item' => __('View Family Member'),
	    'search_items' => __('Search Books'),
		'not_found' =>  __('No family members found'),
		'not_found_in_trash' => __('No family members found in Trash'),
		'parent_item_colon' => '',
		'menu_name' => 'Family'
	  ),
	  'public' => true,
	  'can_export' => true,
	  'publicly_queryable' => true,
	  'register_meta_box_cb' => 'genealogy_add_meta_box',
	  'has_archive' => true,
	  'rewrite' => array('slug' => apply_filters('genealogy_slug_name', 'family'), 'with_front' => false),
	  'exclude_from_search' => false,
	  'hierarchical' => false,
	  '_builtin' => false, // I read that this was necessary, even though discouraged by WP people.
	  'taxonomies' => array('relationship'),
	  'menu_icon' => GENEALOGY_PLUGIN_URL .'assets/images/group.png', // Icon courtesy of famfamfam.com Silk Icon set http://www.famfamfam.com/lab/icons/silk/
	  'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields', 'comments', 'revisions' ),
	);
	register_post_type( 'family', $args, false);
}

function genealogy_admin_init() {
	if(!genealogy_check_compatibility()) { return false; }

	add_filter('manage_edit-family_columns', 'genealogy_add_new_admin_columns');
	add_action('manage_posts_custom_column', 'genealogy_manage_admin_columns', 10, 2);
	add_action('manage_pages_custom_column', 'genealogy_manage_admin_columns', 10, 2);
	add_action('manage_pages_custom_column', 'genealogy_manage_admin_columns', 10, 2);
	add_action('manage_family_posts_columns', 'genealogy_manage_family_admin_columns', 10);
	add_action('save_post', 'genealogy_member_data_save');
	# @include_once('genealogy-quickedit.php'); // Quick Edit not working yet...
}

function genealogy_compability_warning() {
	echo '<div class="error"><p>'.__('The Genealogy plugin requires WordPress 3.1 or greater.', 'genealogy').'</p></div>';
}

function genealogy_check_compatibility($echo = false) {
	global $wp_version,$pagenow;

	if($wp_version && $wp_version < 3.1) {
		if(is_admin() && $pagenow == 'plugins.php' && $echo) {
			add_filter('admin_notices', 'genealogy_compability_warning');
		}
		return false;
	}
	return true;
}

function genealogy_excerpt($excerpt) {
	global $post;

	$post = genealogy_get_person($post);

	$excerpt = genealogy_print_person((array)$post, 'caption=&other=');

	return $excerpt;
}


function genealogy_returnfalse($search) { return false; }
function genealogy_returntrue() { return true; }

function genealogy_head() {
	global $post,$pagenow;

	if(is_admin()) {
		// Edit page
		if($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'family') {
			wp_enqueue_style( 'genealogy-admin', GENEALOGY_PLUGIN_URL.'assets/css/genealogy-admin.css');
		} elseif(is_object($post) && $post->post_type == 'family') { // Single page
			wp_enqueue_style( 'genealogy-datepicker', GENEALOGY_PLUGIN_URL.'assets/css/smoothness/jquery-ui-1.8.11.custom.css');
			wp_enqueue_script('genealogy-admin', GENEALOGY_PLUGIN_URL . 'assets/js/genealogy-admin.js', NULL, array('jquery','jquery-ui-core','jquery-ui-datepicker'));
			wp_enqueue_style( 'genealogy-admin', GENEALOGY_PLUGIN_URL .'assets/css/genealogy-admin.css');
		}
	} else {
		wp_enqueue_style('genealogy', GENEALOGY_PLUGIN_URL . 'assets/css/genealogy.css');
	}
}

if(!function_exists('wp_dequeue_script')) {
	function wp_dequeue_script( $handle ) {
	    global $wp_scripts;
	    if ( !is_a($wp_scripts, 'WP_Scripts') )
	        $wp_scripts = new WP_Scripts();
 		$wp_scripts->dequeue( $handle );
 	}
}
if(!function_exists('wp_dequeue_style')) {
	function wp_dequeue_style( $handle ) {
	    global $wp_styles;
	    if ( !is_a($wp_styles, 'WP_Styles') )
	        $wp_styles = new WP_Styles();
 		$wp_styles->dequeue( $handle );
 	}
}

function genealogy_member_data_save($post_id) {
	// verify this came from the our screen and with proper authorization.
	if (
		isset($_POST['family_member_data_noncename']) &&
		!wp_verify_nonce( $_POST['family_member_data_noncename'], 'family_member_data'.$post_id ) &&
		((!isset($_POST['screen']) || $_POST['screen'] != 'edit-family') && $_POST['action'] !='inline-save')
	) {
		return $post_id;
	}
	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return $post_id;

	// Check permissions
	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;


	// OK, we're authenticated: we need to find and save the data
	$post = get_post($post_id);
	if ($post->post_type == 'family') {
		$existing = get_post_meta($post->ID, '_family_member_data', true);
		$new = array();
		if(isset($_POST['family_member_data']) && is_array($_POST['family_member_data'])) {
			foreach($_POST['family_member_data'] as $key => $val) {
				$new[$key] =  esc_attr($val);
				if(empty($val) && $val !== '-1') {
					unset($_POST['family_member_data'][$key]);
				} else {
					$_POST['family_member_data'][$key] = esc_attr($val);
				}
			}

			if(!empty($_POST['family_member_data']['living'])) {
				if($_POST['family_member_data']['living'] == 'Unknown' || $_POST['family_member_data']['living'] == 'Living') {
					unset($_POST['family_member_data']['death_date'], $_POST['family_member_data']['death_date'], $_POST['family_member_data']['death_reason'], $_POST['family_member_data']['death_date_precision_date_type']);
					unset($new['death_date'], $new['death_date'], $new['death_reason'], $new['death_date_precision_date_type']);
				}
			}

			if(!empty($_POST['family_member_data']['wife_1'])) {
				if(!empty($_POST['family_member_data']['wife_2'])) {
					// There is a wife 1 and wife 2. Wife can go.
					unset($_POST['family_member_data']['wife'], $_POST['family_member_data']['wife_timespan_checked'], $_POST['family_member_data']['wife_timespan_from'],$_POST['family_member_data']['wife_timespan_to'],$_POST['family_member_data']['wife_timespan_reason']);
					unset($new['wife'], $new['wife_timespan_checked'], $new['wife_timespan_from'],$new['wife_timespan_to'],$new['wife_timespan_reason']);
				} else {
					// There's not a wife 1; only wife.
					$_POST['family_member_data']['wife'] = $_POST['family_member_data']['wife_1'];
					$_POST['family_member_data']['wife_timespan_checked'] = $_POST['family_member_data']['wife_1_timespan_checked'];
					$_POST['family_member_data']['wife_timespan_from'] = $_POST['family_member_data']['wife_1_timespan_from'];
					$_POST['family_member_data']['wife_timespan_to'] = $_POST['family_member_data']['wife_1_timespan_to'];
					$_POST['family_member_data']['wife_timespan_reason'] = $_POST['family_member_data']['wife_1_timespan_reason'];
					$new['wife'] = $_POST['family_member_data']['wife_1'];
					$new['wife_timespan_checked'] = $_POST['family_member_data']['wife_1_timespan_checked'];
					$new['wife_timespan_from'] = $_POST['family_member_data']['wife_1_timespan_from'];
					$new['wife_timespan_to'] = $_POST['family_member_data']['wife_1_timespan_to'];
					$new['wife_timespan_reason'] = $_POST['family_member_data']['wife_1_timespan_reason'];
					unset($_POST['family_member_data']['wife_1'], $_POST['family_member_data']['wife_1_timespan_checked'], $_POST['family_member_data']['wife_1_timespan_from'],$_POST['family_member_data']['wife_1_timespan_to'],$_POST['family_member_data']['wife_1_timespan_reason']);
					unset($new['wife_1'], $new['wife_1_timespan_checked'], $new['wife_1_timespan_from'],$new['wife_1_timespan_to'],$new['wife_1_timespan_reason']);
				}
			}

			if(!empty($_POST['family_member_data']['husband_1'])) {
				if(!empty($_POST['family_member_data']['husband_2'])) {
					// There is a husband 1 and husband 2. husband can go.
					unset($_POST['family_member_data']['husband'], $_POST['family_member_data']['husband_timespan_checked'], $_POST['family_member_data']['husband_timespan_from'],$_POST['family_member_data']['husband_timespan_to'],$_POST['family_member_data']['husband_timespan_reason']);
					unset($new['husband'], $new['husband_timespan_checked'], $new['husband_timespan_from'],$new['husband_timespan_to'],$new['husband_timespan_reason']);
				} else {
					// There's not a husband 1; only husband.
					$_POST['family_member_data']['husband'] = $_POST['family_member_data']['husband_1'];
					$_POST['family_member_data']['husband_timespan_checked'] = $_POST['family_member_data']['husband_1_timespan_checked'];
					$_POST['family_member_data']['husband_timespan_from'] = $_POST['family_member_data']['husband_1_timespan_from'];
					$_POST['family_member_data']['husband_timespan_to'] = $_POST['family_member_data']['husband_1_timespan_to'];
					$_POST['family_member_data']['husband_timespan_reason'] = $_POST['family_member_data']['husband_1_timespan_reason'];
					$new['husband'] = $_POST['family_member_data']['husband_1'];
					$new['husband_timespan_checked'] = $_POST['family_member_data']['husband_1_timespan_checked'];
					$new['husband_timespan_from'] = $_POST['family_member_data']['husband_1_timespan_from'];
					$new['husband_timespan_to'] = $_POST['family_member_data']['husband_1_timespan_to'];
					$new['husband_timespan_reason'] = $_POST['family_member_data']['husband_1_timespan_reason'];
					unset($_POST['family_member_data']['husband_1'], $_POST['family_member_data']['husband_1_timespan_checked'], $_POST['family_member_data']['husband_1_timespan_from'],$_POST['family_member_data']['husband_1_timespan_to'],$_POST['family_member_data']['husband_1_timespan_reason']);
					unset($new['husband_1'], $new['husband_1_timespan_checked'], $new['husband_1_timespan_from'],$new['husband_1_timespan_to'],$new['husband_1_timespan_reason']);
				}
			}


		} else {
			return $post_id;
		}

		if(isset($_POST['screen']) && $_POST['screen'] != 'edit-family' && $_POST['action'] !='inline-save') {
			update_post_meta($post->ID, '_family_member_data', $new );
		} elseif(isset($_POST['family_member_data'])) {
			update_post_meta($post->ID, '_family_member_data', $_POST['family_member_data'] );
		}
               return $post_id;
	}
	return $post_id;
}

function genealogy_member_data_metabox($post) {

	$data = get_post_meta($post->ID, '_family_member_data', true);

	$family = genealogy_get_family($post);

	global $post;


	$parents = '';
	$parents .= genealogy_make_select($family, $data, 'mother');
	$parents .= genealogy_make_select($family, $data, 'father');

	if(!empty($parents)) {
		echo genealogy_make_fieldset($parents, __('Parents', 'genealogy'), 'left');
	}

	$sex = '';
	$sex .= genealogy_make_radio('sex', 'male', $data);
	$sex .= genealogy_make_radio('sex', 'female', $data);
	#$sex .= genealogy_make_radio('sex', 'other', $data);
	echo genealogy_make_fieldset($sex, __('Sex', 'genealogy'), 'right');


	$spouses = '';
	if(!empty($data['wife_1'])) {
		$i = 0; while($i < 20) {
			if(isset($data['wife_'.$i])) { $spouses .=  genealogy_make_select($family, $data, 'wife_'.$i, true); }
			$i++;
		}
	} else { $spouses .=  genealogy_make_select($family, $data, 'wife', true); }
	if(!empty($data['husband_1'])) {
		$i = 0; while($i < 20) {
			if(isset($data['husband_'.$i])) { $spouses .= genealogy_make_select($family, $data, 'husband_'.$i, true); }
			$i++;
		}
	} else { $spouses .= genealogy_make_select($family, $data, 'husband', true); }

	if(!empty($spouses)) {
		echo genealogy_make_fieldset($spouses, __('Spouses', 'genealogy'), 'clear');
	}

	$name = '';
	$name .= genealogy_make_text('first_name', '', __('First Name', 'genealogy'), $data, '');
	$name .= genealogy_make_text_recursive('middle_name', '', __('Middle Name', 'genealogy'), $data, 'clone');
	$name .= genealogy_make_text('last_name', '', __('Last Name', 'genealogy'), $data, '');
	$name .= genealogy_make_text('maiden_name', '', __('Maiden Name (optional)', 'genealogy'), $data, '');
	echo genealogy_make_fieldset($name, __('Name', 'genealogy'), 'left');

	$birth = '';
	$birth .= genealogy_make_text('birth_date', '', __('Date of Birth', 'genealogy'), $data, 'datepicker');
	$birth .= genealogy_make_text('birth_location', '', __('Birth place', 'genealogy'), $data, '');
	$living = '';
	$living .=  genealogy_make_radio('living', __('Living', 'genealogy'), $data, true);
	$living .= genealogy_make_radio('living', __('Deceased', 'genealogy'), $data);
	$living .= genealogy_make_radio('living', __('Unknown', 'genealogy'), $data);
	$death = '';
	$death .= genealogy_make_select_date_type('death_date_precision', array('ON' => 'Exactly', 'BEF'=>'Before', 'AFT'=>'After', 'ABT' => 'About'), 'Date Precision', $data);
	$death .= genealogy_make_text('death_date', '', __('Date of Death', 'genealogy'), $data, 'datepicker', 'Leave empty if still alive');
	$death .= genealogy_make_text('death_location', '', __('Death Location', 'genealogy'), $data, '');
	$death .= genealogy_make_text('death_reason', '', __('Cause of Death', 'genealogy'), $data, '');
	echo genealogy_make_fieldset($birth, __('Birth', 'genealogy'), 'right');
	echo genealogy_make_fieldset($living, __('Living', 'genealogy'), 'right');
	echo genealogy_make_fieldset($death, __('Death', 'genealogy'), 'right');

	$lifeinfo = '';
	$lifeinfo .= genealogy_make_text_recursive('school', '', __('Schooling', 'genealogy'), $data, 'clone');
	$lifeinfo .= genealogy_make_text_recursive('religion', '', __('Religion', 'genealogy'), $data, 'clone');
	$lifeinfo .= genealogy_make_text_recursive('profession', '', __('Profession', 'genealogy'), $data, 'clone');
	$lifeinfo .= genealogy_make_text_recursive('company', '', __('Company', 'genealogy'), $data, 'clone');
	$lifeinfo .= genealogy_make_text_recursive('location', '', __('Location', 'genealogy'), $data, 'clone');
	$lifeinfo .= genealogy_make_text_recursive('event', '', __('Life Event', 'genealogy'), $data, 'clone');
	$lifeinfo .= genealogy_make_text('activities', '', __('Activities', 'genealogy'), $data, '');
	$lifeinfo .= genealogy_make_text('bio_notes', '', __('Bio Notes', 'genealogy'), $data, '');
	echo genealogy_make_fieldset($lifeinfo, __('Additional Information', 'genealogy'));

	$contactinfo = '';
	$contactinfo .= genealogy_make_text_recursive('phone', '', __('Phone', 'genealogy'), $data, 'clone');
	$contactinfo .= genealogy_make_text_recursive('email', '', __('Email', 'genealogy'), $data, 'clone');
	$contactinfo .= genealogy_make_text_recursive('address', '', __('Address', 'genealogy'), $data, 'clone');

	echo genealogy_make_fieldset($contactinfo, __('Contact Information', 'genealogy'));

	echo '<input type="hidden" name="family_member_data_noncename" id="family_member_data_noncename" value="'.wp_create_nonce( 'family_member_data'.$post->ID ).'" />';

}

function genealogy_make_fieldset($data, $name, $position = '') {
	if($position != 'clear') {
		$position = empty($position) ? 'aligncenter' : 'align'.$position;
	}
	return '<fieldset class="'.sanitize_title($name).' '.$position.'"><legend>'.esc_html($name).'</legend>'.$data.'</fieldset>';
}

function genealogy_make_select_date_type($selector = null, $options = array(), $selectTitle = "Option", $data = array()) {
	$selected = ' selected="selected"';
	$id = sanitize_title('family_member_data_'.$selector);
	$output = '<select name="family_member_data['.$selector.'_date_type]" id="'.$id.'">
					<option value=""'; if(!isset($data[$selector.'_date_type']) || empty($data[$selector.'_date_type'])) { $output .= $selected; }
			$output .= '>Select a '.$selectTitle.'</option>';

	foreach($options as $key => $value) {
		$output .= '<option value="'.$key.'"'; if(isset($data[$selector.'_date_type']) && $data[$selector.'_date_type'] == $key) { $output .= $selected; }
		$output .= '>'.$value.'</option>';
	}
	$output .= '</select>';
	return $output;
}

function genealogy_make_text_recursive($name=null, $value = null, $label = null, $data = array(), $class = 'text', $notes = '') {
	$out = '';
	if(isset($data[$name.'_1'])) {
		$i = 0;
		while($i < 20) {
			if(isset($data[$name.'_'.$i])) {
				$out .= genealogy_make_text($name.'_'.$i, $value, $label, $data, $class, $notes);
			}
			$i++;
		}
	} else {
		$out .= genealogy_make_text($name, $value, $label, $data, $class, $notes);
	}
	return $out;
}

add_filter('the_content', 'genealogy_print_information');

function genealogy_print_information($content) {
if(is_admin()) { return $content; }
	global $post,$wp_rewrite;
	if($post->post_type != 'family') { return $content; }
	$output = $content;
	$post = genealogy_get_person($post);
	$output .= "<h2>".__('Personal Information', 'genealogy')."</h2>".genealogy_print_person((array)$post);
	$children = @genealogy_print_relationships((array)$post);

	$output .= $children;
	return $output;
}


function genealogy_print_relationships($thePost) {

	$children = @genealogy_get_relationships($thePost);
	if(empty($children)) { return false; }

	$blood = $ouput = $stepchildren = $inlaws = $siblings = $grandchildren = $greatgrandchildren = $greatgreatgrandchildren = false;

	if(!empty($children['sibling'])) {
		foreach($children['sibling'] as $key=>$child) {
			// Only show husband or wife information for less of a huge page
			foreach($child as $key => $info) { if($key != 'wife' && $key != 'husband' && $key != 'ID'  && $key != 'post_title') { unset($child->$key); }}
			$siblings .= genealogy_print_person($child);
		}
		unset($children['sibling']);
	}

	if(!empty($children['halfsibling'])) {
		foreach($children['halfsibling'] as $key=>$child) {
			// Only show husband or wife information for less of a huge page
			foreach($child as $key => $info) { if($key != 'wife' && $key != 'husband' && $key != 'ID'  && $key != 'post_title') { unset($child->$key); }}
			$halfsiblings .= genealogy_print_person($child);
		}
		unset($children['halfsibling']);
	}

	if(!empty($children['inlaws'])) {
		foreach($children['inlaws'] as $key=>$child) {
			// Only show husband or wife information for less of a huge page
			foreach($child as $key => $info) { if($key != 'wife' && $key != 'husband' && $key != 'ID'  && $key != 'post_title') { unset($child->$key); }}
			$inlaws .= genealogy_print_person($child);
		}
		unset($children['inlaws']);
	}

	if(!empty($children['stepchildren'])) {
		foreach($children['stepchildren'] as $key=>$child) {
			// Only show dad or mom information for less of a huge page
			foreach($child as $key => $info) { if($key != 'mother' && $key != 'father' && $key != 'ID' && $key != 'post_title') { unset($child->$key); }}
			$stepchildren .= genealogy_print_person($child);
		}
		unset($children['stepchildren']);
	}

	if(!empty($children['grandchildren'])) {
		foreach($children['grandchildren'] as $key=>$child) {
			// Only show dad or mom information for less of a huge page
			#if(!is_array($child)) { $child = (object)$child; }
			foreach($child as $key => $info) { if($key != 'mother' && $key != 'father' && $key != 'ID' && $key != 'post_title') { unset($child->$key); }}
			$grandchildren .= genealogy_print_person($child);
		}
		unset($children['grandchildren']);
	}

	if(!empty($children['greatgrandchildren'])) {
		foreach($children['greatgrandchildren'] as $key=>$child) {
			// Only show dad or mom information for less of a huge page
			#if(!is_array($child)) { $child = (object)$child; }
			foreach($child as $key => $info) { if($key != 'mother' && $key != 'father' && $key != 'ID' && $key != 'post_title') { unset($child->$key); }}
			$greatgrandchildren .= genealogy_print_person($child);
		}
		unset($children['greatgrandchildren']);
	}

	if(!empty($children['greatgreatgrandchildren'])) {
		foreach($children['greatgreatgrandchildren'] as $key=>$child) {
			// Only show dad or mom information for less of a huge page
			#if(!is_array($child)) { $child = (object)$child; }
			foreach($child as $key => $info) { if($key != 'mother' && $key != 'father' && $key != 'ID' && $key != 'post_title') { unset($child->$key); }}
			$greatgreatgrandchildren .= genealogy_print_person($child);
		}
		unset($children['greatgreatgrandchildren']);
	}

	if(!empty($children['children'])) {
		foreach($children['children'] as $key=>$child) {
			// Only show dad or mom information for less of a huge page
			#if(!is_array($child)) { $child = (object)$child; }
			foreach($child as $key => $info) { if($key != 'mother' && $key != 'father' && $key != 'ID' && $key != 'post_title') { unset($child->$key); }}
			$blood .= genealogy_print_person($child);
		}
	}

	if($siblings) { $output .= genealogy_make_relationship_table(__('Siblings', 'genealogy'), $siblings, 'h2'); }
	if($halfsiblings) { $output .= genealogy_make_relationship_table(__('Half-Siblings', 'genealogy'), $halfsiblings, 'h2'); }
	if($blood) { $output .= genealogy_make_relationship_table(__('Children', 'genealogy'), $blood, 'h2'); }

	if($inlaws) { $output .= genealogy_make_relationship_table(__('Children-in-Law', 'genealogy'), $inlaws); }
	if($stepchildren) { $output .= genealogy_make_relationship_table(__('Step-Children', 'genealogy'), $stepchildren); }
	if($grandchildren) { $output .= genealogy_make_relationship_table(__('Grand-Children', 'genealogy'), $grandchildren); }
	if($greatgrandchildren) { $output .= genealogy_make_relationship_table(__('Great-Grand-Children', 'genealogy'), $greatgrandchildren); }
	if($greatgreatgrandchildren) { $output .= genealogy_make_relationship_table(__('Great-Great-Grand-Children', 'genealogy'), $greatgreatgrandchildren); }

	return $output;
}

function genealogy_make_relationship_table($headline = '', $content = '', $tag = 'h3') {

$ImageHead = apply_filters('genealogy_show_relationship_thumbnails', true) ? '<th scope="col" class="genealogy_row_image"></th>' : '';
$Name = __('Name', 'genealogy');
$Birth = __('Birth', 'genealogy');
$Death = __('Death', 'genealogy');
$class = str_replace('-', '_', sanitize_title($headline).'_table');
$out = <<<EOD
<$tag>$headline</$tag>
	<table class="genealogy_relationships_table $class" cellspacing="0">
		<thead>
			$ImageHead
			<th scope="col" class="genealogy_row_name">$Name</th>
			<th scope="col" class="genealogy_row_birth">$Birth</th>
			<th scope="col" class="genealogy_row_death">$Death</th>
		</thead>
		<tbody>
			$content
		</tbody>
	</table>
EOD;

	return $out;
}

function genealogy_make_rows_spouses($person) {
	// Spouses
	$spouses = ''; $empty = true;
	if(!is_object($person)) { return $person; } if(is_array($person)) { $person = (object)$person; }
	foreach($person as $key => $info) {
		if(is_string($info)) { $info = trim($info); }
		if(preg_match('/_checked/ism',$key) || $key == 'ID' || empty($info) || $info == '-1') { continue; }
		$key = str_replace('_', ' ', $key);
		if(preg_match('/^(husband|wife)(\s+)?([0-9]+)?$/ism', $key, $matches)) {
			$from = $to = $reason = '';
			$spouse = get_posts(array('numberposts'=>1,'post_type'=>'family','include'=>$info));
			$spouses .= '<tr><th scope="row">'.ucwords($key).'</th><td><a href="'.get_permalink($spouse[0]->ID).'">'.apply_filters( 'the_title' , $spouse[0]->post_title ).'</a>';

			$spouseType = $matches[1];
			if(empty($matches[3])) { $num = ''; } else { $num = '_'.$matches[3]; }

				unset($person->{"{$spouseType}{$num}"});

			if(!empty($person->{"{$spouseType}{$num}_timespan_from"})) {
				$from = $person->{"{$spouseType}{$num}_timespan_from"};
				unset($person->{"{$spouseType}{$num}_timespan_from"});
			}
			if(!empty($person->{"{$spouseType}{$num}_timespan_to"})) {
				$to = $person->{"{$spouseType}{$num}_timespan_to"};
				unset($person->{"{$spouseType}{$num}_timespan_to"});
			}
			if(!empty($person->{"{$spouseType}{$num}_timespan_reason"})) {
				$reason = $person->{"{$spouseType}{$num}_timespan_reason"};
				unset($person->{"{$spouseType}{$num}_timespan_reason"});
			}

			if(!empty($from) || !empty($to)) {
				$spouses .= " <small>({$from} - {$to}";
				if(!empty($reason)) { $spouses .= "; {$reason})";} else { $spouses .= ')';}
				$spouses .= '</small>';
			}
			$spouses .= '</td></tr>';
			$empty = false;
		}
	}
	if(!$empty) { return array($person, $spouses); } else { return array($person, ''); }
}

function genealogy_make_rows_parents($person) {
	global $post;
	$empty = true; $father = $mother = '';
	if(!is_object($person)) { return $person; } if(is_array($person)) { $person = (object)$person; }
	foreach($person as $key => $info) {
		if(is_string($info)) { $info = trim($info); }

		if(preg_match('/_checked/ism',$key) || $key == 'ID' || empty($info) || $info == '-1') { continue; }
		$key = str_replace('_', ' ', $key);
		if($key == 'mother' || $key == 'father') {
			$parent = get_posts(array('numberposts'=>1,'post_type'=>'family','include'=>$info));
			if($key == 'mother') {
				$mother = '<div class="genealogy_mother"><a href="'.get_permalink($parent[0]->ID).'" title="'.__('Mother','genealogy').'">'.apply_filters( 'the_title' , $parent[0]->post_title ).'</a></div>';
			} else {
				$father = '<div class="genealogy_father"><a href="'.get_permalink($parent[0]->ID).'" title="'.__('Father','genealogy').'">'.apply_filters( 'the_title' , $parent[0]->post_title ).'</a></div>';
			}
			unset($person->{"{$key}"});
			$empty = false;
		}
	}

	$parents = '<tr><th scope="row">'.__('Parents', 'genealogy').'</th><td>'.$mother.$father.'</td></tr>';

	if(!$empty) { return array($person, $parents); } else { return array($person, ''); }
	return '';
}

function genealogy_make_rows_living($person, $settings) {
	global $post;

	extract($settings, EXTR_PREFIX_ALL, 'opt'); // The $parents settings becomes $opt_parents
	$full = $opt_full;
	$empty = true; $living = '';
	if(!is_object($person)) { return $person; } if(is_array($person)) { $person = (object)$person; }
	$birth = $death = false;
	foreach($person as $key => $info) {
		if(is_string($info)) { $info = trim($info); }

		if(preg_match('/_checked/ism',$key) || $key == 'ID' || empty($info) || $info == '-1') { continue; }

		${"{$key}"} = false;

		if(preg_match('/(birth|death|living)/ism', $key)) {
			if(preg_match('/birth/ism', $key)) {
				$birth = true;
			}
			if(preg_match('/death/ism', $key)) {
				$death = true;
			}

			${"{$key}"} = $info;
			#$living .= ' '.$info;

			unset($person->{"{$key}"});
			continue;
		}
	}
	$alive = true;
	if(strtolower($living) == 'deceased') { $alive = false; }

	if($birth) {
		$birth = '';
		if(apply_filters('genealogy_show_birth_date', true) && ((apply_filters('genealogy_show_birth_date_of_living', true) && $alive) || !$alive)) {
			$birth .= !empty($birth_date_precision_date_type) ? $birth_date_precision_date_type : '';
			$birth .= !empty($birth_date) ? ' '.$birth_date : '';
		} elseif(!apply_filters('genealogy_show_birth_date_of_living', true)) {
		#	$birth = '(Living)';
		}
		if(apply_filters('genealogy_show_birth_location', true) && ((apply_filters('genealogy_show_birth_location_of_living', true) && $alive) || !$alive)) {
			if(!empty($birth)) {
				$birth .= (!empty($birth_date) && !empty($birth_location)) ? __(' in ', 'genealogy') : '';
			}
			$birth .= isset($birth_location) ? $birth_location : '';
		}

		if($full && !empty($birth)) {
			$birth = '<tr><th scope="row">'.__('Birth', 'genealogy').'</th><td>'.$birth.'</td></tr>';
		} elseif(!$full) {
			$birth = '<td>'.$birth.'</td>';
		}
	}
	if($death || !$alive) {
		$death = '';
		$death .= $death_date_precision_date_type ? $death_date_precision_date_type : '';
		$death .= $death_date ? ' '.$death_date : '';
		if($full || $opt_death_location) {
			$death .= $death_location ? __(' in ', 'genealogy').$death_location : '';
		}
		if($full || $opt_death_reason) {
			$death .= $death_reason ? __(' from ', 'genealogy').$death_reason : '';
		}

		if(!$alive && empty($death)) { $death = __('Deceased', 'genealogy'); }

		if($full) {
			$death = '<tr><th scope="row">'.__('Death', 'genealogy').'</th><td>'.$death.'</td></tr>';
		} else {
			$death = '<td>'.$death.'</td>';
		}
	}

	return array($person, $birth, $death);

}

function genealogy_make_rows_name($person, $full = false) {
	global $post;
	$empty = true; $name = '';
	if(!is_object($person)) { return $person; } if(is_array($person)) { $person = (object)$person; }
	foreach($person as $key => $info) {
		if(is_string($info)) { $info = trim($info); }

		if(preg_match('/_checked/ism',$key) || $key == 'ID' || empty($info) || $info == '-1') { continue; }
		${"{$key}"} = '';
		if(preg_match('/\_name/ism', $key)) {
			${"{$key}"} = $info;
			if($key == 'maiden_name') {
				$name .= ' (n&eacute;e '.$info.')';
			} else {
				$name .= ' '.$info;
			}
			unset($person->{"{$key}"});
			continue;
		}
	}

	if(empty($name) && isset($person->post_title)) {
		$name = $person->post_title;
	}

	if(!$full) {
		if(!empty($maiden_name)) {
			$name = $first_name . ' '.$maiden_name;
		} elseif(!empty($first_name) && !empty($last_name)) {
			$name = $first_name . ' '.$last_name;
		}
		if(empty($name) && isset($person->post_title)) {
			$name = $person->post_title;
		}
	}

	unset($person->post_title);

	return array($person, $name);
}

function genealogy_make_rows_other($person, $full = false) {
	global $post;
	$empty = true; $out = '';
	if(!is_object($person)) { return $person; } if(is_array($person)) { $person = (object)$person; }

	foreach($person as $key => $info) {
		if(is_string($info)) { $info = trim($info); }

		if(preg_match('/_checked/ism',$key) || $key == 'ID' || empty($info) || $info == '-1') { continue; }

		$key = str_replace('_', ' ', $key);
		$out .= '<tr><th scope="row">'.ucwords((string)$key).'</th><td>'.(string)$info.'</td></tr>';
		$empty = false;
	}

	return array($person, $out);
}

function genealogy_get_value($person, $requestedKey, $array = false, $unset = false) {
	if(!is_object($person)) { return $person; } if(is_array($person)) { $person = (object)$person; }
	foreach($person as $key => $info) {
		if(is_string($info)) { $info = trim($info); }

		if(preg_match('/_checked/ism',$key) || $key == 'ID' || empty($info) || $info == '-1') { continue; }

		if($key == $requestedKey) {
			if($unset) { unset($person->{"{$key}"}); }
			if($array) { return array($person, $info); }
			return $info;
		}
	}
	if($array) { return array($person, false); }
	return false;
}

function genealogy_print_person($person, $args = array()) {
	global $post;
	$out = '';

	$defaults = array(
		'full' => false,
		'caption' => true,
		'thumb' => true,
		'birth' => true,
		'death' => true,
		'death_reason' => false,
		'death_location' => false,
		'parents' => true,
		'spouses' => true,
		'email' => true,
		'phone' => true,
		'address' => true,
		'other' => true
	);

	$settings = wp_parse_args($args, apply_filters('genealogy_default_settings', $defaults));

	extract($settings, EXTR_PREFIX_ALL, 'opt'); // The $parents settings becomes $opt_parents

	if(is_array($person)) {
		if(!empty($person[0]) && is_object($person[0])) {
			$person = $person[0];
		} else {
			$person = (object)$person;
		}
	} elseif(!is_object($person) && !is_array($person)) {
		$person = (string)$person;
		$person = @get_post($person);
		$person = @genealogy_get_person($person);
	}
	if(empty($person)) { return false; }

	$full = ($post->ID == $person->ID || $opt_full);
	$settings['full'] = $full;

	$title =  $full ? "<strong>".apply_filters( 'the_title' , $person->post_title )."</strong>" : '<a href="'.get_permalink($person->ID).'">'.apply_filters( 'the_title' , $person->post_title ).'</a>';

	unset($person->post_author,$person->post_date,$person->post_date_gmt,$person->post_status,$person->post_name,$person->ping_status,$person->post_modified_gmt,$person->guid,$person->filter, $person->comment_status, $person->post_type, $person->permalink,$person->post_content);


	$birth = $death = $spouses = $parents = $other = $sex = $alive = $name = false;

	if($full && (apply_filters('genealogy_show_last_modified_to_admins', false) || apply_filters('genealogy_show_last_modified', false))) {
		if((apply_filters('genealogy_show_last_modified_to_admins', false) && current_user_can('administrator')) || apply_filters('genealogy_show_last_modified', false)) {
			$person->last_modified = $post->post_modified;
		}
	}
	unset($person->post_modified);

	$results = genealogy_make_rows_name($person, $full); 		$person = $results[0];	$name = $results[1];
	$results = genealogy_make_rows_spouses($person, $full);	$person = $results[0];	$spouses = $results[1];
	$results = genealogy_make_rows_parents($person, $full);	$person = $results[0];	$parents = $results[1];
	$results = genealogy_make_rows_living($person, $settings); 	$person = $results[0];	$birth = $results[1]; $death = $results[2];
	$results = genealogy_get_value($person, 'sex', true, true); 	$person = $results[0];	$sex = $results[1];
	$results = genealogy_get_value($person, 'living', true, false);	$person = $results[0];	$alive = $results[1];
	$results = genealogy_make_rows_other($person, $full);			$person = $results[0];	$other = $results[1];
	if($full) {
		$thumb = false;
		if(function_exists('get_the_post_thumbnail') && $thumb = get_the_post_thumbnail($person->ID, apply_filters('genealogy_thumbnail_size', array(150,150)))) {
			$large = wp_get_attachment_image_src( get_post_thumbnail_id($person->ID), 'large');
		}
		$out = '<table class="sex_'.$sex.'">'."\n\t";
		if($opt_caption) { $out .= '<caption>'.$title.'<span></span></caption>'."\n\t"; }
		$out .= '<tbody>'."\n\t";
		if($thumb) { $out .= '<tr><td colspan="2" style="text-align:center;"><a href="'.$large[0].'" rel="thickbox colorbox lightbox">'.$thumb.'</a></td></tr>'."\n\t"; }
		$out .= '<tr><th scope="row">'.__('Name', 'genealogy').'</th><td>'.$name.'</td></tr>'."\n\t";
		if($opt_birth) { $out .= $birth; }
		if($opt_death) { $out .= $death; }
		if($opt_parents) { $out .= $parents; }
		if($opt_spouses) { $out .= $spouses; }
		if($opt_other) { $out .= $other; }
		$out .= '</tbody></table>'."\n\t";
	} else {
		$thumb = '';
		if($opt_thumb && apply_filters('genealogy_show_relationship_thumbnails', true)) {
			$thumb = (function_exists('get_the_post_thumbnail') && $thumb = get_the_post_thumbnail($person->ID, array(60,60))) ? '<td class="genealogy_row_image"><a href="'.get_permalink($person->ID).'">'.$thumb.'</a></td>' : '<td class="genealogy_row_image"></td>'."\n\t";
		}
		$name = '<td class="genealogy_row_name"><a href="'.get_permalink($person->ID).'">'.$name.'<span class="sex_'.sanitize_title($sex).'">asdasds</span></a>';
		$birth = $birth ? $birth : '<td class="genealogy_row_birth">&nbsp;</td>'."\n\t";
		$death = $death ? $death : '<td class="genealogy_row_death">&nbsp;</td>'."\n\t";
		$out = "\n\t".'<tr>'.$thumb.$name.$birth.$death.'</tr>'."\n\t";
	}


	return $out;
}

#function genealogy_get_timeline($)

add_shortcode('geneology', 'genealogy_do_shortcode');
add_shortcode('genealogy', 'genealogy_do_shortcode');

function genealogy_do_shortcode($atts = array(), $content= null) {
	$defaults = array(
		'full' => false,
		'caption' => true,
		'thumb' => true,
		'birth' => true,
		'death' => true,
		'death_reason' => false,
		'death_location' => false,
		'parents' => true,
		'spouses' => true,
		'other' => true,
		'id' => NULL,
		'slug' => '',
		'full' => true
	);

	$settings = shortcode_atts($defaults, $atts);

	extract($settings);

	if($id) {
		$content = genealogy_print_person($id, $settings);
	} elseif($slug) {
		$content = genealogy_print_person($slug, $settings);
	} else {
		$content = genealogy_build_tree().$content;
	}

	return $content;
}

function genealogy_build_tree() {
	$family = genealogy_get_family();

	$out = '<ul>';
	foreach($family as $member) {
		$member = genealogy_get_person($member);
		$member = genealogy_get_relationships($member, 'object');
		$children = $grandchildren = $greatgrandchildren = array();
		extract((array)$member);
/*
		$member->level = 0;
		if(!empty($children) && is_array($children)) { $member->level = 1; }
		if(!empty($grandchildren) && is_array($grandchildren)) { $member->level = 2; }
		if(!empty($greatgrandchildren) && is_array($greatgrandchildren)) { $member->level = 3; }
*/
		$out .= '<li><a href="'.get_permalink($member->ID).'">'.$member->post_title.'</a><br /></li>';
	}
	$out .= '</ul>';
	return $out;
}

function genealogy_build_map() {

	$family = genealogy_get_family();

	echo '<ul>';
	foreach($family as $key=>$member) {
		$family[$key] = get_relationships_worker($member, $family, 'object');
		echo '<li><a href="'.get_permalink($member->ID).'">'.$member->post_title.'</a></li>';
	}
	echo '</ul>';

}

function get_relationships_worker($mainPerson, $family = array(), $type = 'array') {
	$relationship = $children = $childrenids = $grandchildrenids = $greatgrandchildrenids = $greatgreatgrandchildrenids = array();

	if(empty($family) && (!is_object($family) || !is_array($family))) { return; }

	if(is_array($mainPerson)) { $mainPerson = (object)$mainPerson; }

	foreach($family as $key => $person) {
		if((!empty($person->mother) || !empty($person->father)) && (!empty($person->mother) && (int)$person->mother == (int)$mainPerson->ID || !empty($person->father) && (int)$person->father == (int)$mainPerson->ID)) {
			$relationship['children'][] = $person->ID;
			$childrenids[] = $person->ID;
		}
	}

	foreach($family as $key => $person) {
		if($person->ID == $mainPerson->ID) { continue; }

		#$person = genealogy_get_person($person);

		if(
			(!empty($person->mother) && !empty($mainPerson->mother) && (!empty($person->father) && !empty($mainPerson->father))) &&
			$person->father === $mainPerson->father &&
			$person->mother === $mainPerson->mother &&
			$mainPerson->father != '-1' && $mainPerson->mother != '-1'
		) {
			$relationship['sibling'][] = $person->ID;
		} elseif(
				(!empty($mainPerson->father) && !empty($person->father)  && $mainPerson->father === $person->father && $mainPerson->father != '-1') ||
				(!empty($mainPerson->mother) && !empty($person->mother) && $mainPerson->mother === $person->mother && $mainPerson->mother != '-1')
		) {
			$relationship['halfsibling'][] = $person->ID;
		}

		if(!empty($person->wife) && in_array((int)$person->wife, $childrenids) || !empty($person->husband) && in_array((int)$person->husband, $childrenids)) {
			$relationship['inlaws'][] = $person->ID;
		}

		if(!empty($person->father)) {
			if(!empty($mainPerson->husband)) {
				if((int)$person->father === (int)$mainPerson->husband && (int)$person->mother !== (int)$mainPerson->ID) {
					$relationship['stepchildren'][] = $person->ID;
				}
			}
			for($i=1; $i < 20; $i++) {
				if(!empty($mainPerson->{"husband_{$i}"}) && ((int)$person->father === (int)$mainPerson->{"husband_{$i}"}) && (int)$person->mother !== (int)$mainPerson->ID) {
					$relationship['stepchildren'][] = $person->ID;
				}
			}
		}
		if(!empty($person->mother)) {
			if(!empty($mainPerson->wife)) {
				if((int)$person->mother === (int)$mainPerson->wife && (int)$person->father !== (int)$mainPerson->ID) {
					$relationship['stepchildren'][] = $person->ID;
				}
			}
			for($i=1; $i < 20; $i++) {
				if(!empty($mainPerson->{"wife_{$i}"}) && ((int)$person->mother === (int)$mainPerson->{"wife_{$i}"}) && (int)$person->father !== (int)$mainPerson->ID) {
					$relationship['stepchildren'][] = $person->ID;
				}
			}
		}
	}
	foreach($family as $key => $person) {
		if(isset($person->father) && in_array((int)$person->father, $childrenids) || isset($person->mother) && in_array((int)$person->mother, $childrenids)) {
			$relationship['grandchildren'][] = $person->ID;
			$grandchildrenids[] = $person->ID;
		}
	}

	foreach($family as $key => $person) {
		if(isset($person->father) && in_array($person->father, $grandchildrenids) || isset($person->mother) && in_array($person->mother, $grandchildrenids)) {
			$relationship['greatgrandchildren'][] = $person->ID;
			$greatgrandchildrenids[] = $person->ID;
		}
	}

	foreach($family as $key => $person) {
		if(!empty($person->father) && in_array($person->father, $greatgrandchildrenids) || !empty($person->mother) && in_array($person->mother, $greatgrandchildrenids)) {
			$relationship['greatgreatgrandchildren'][] = $person->ID;
			$greatgreatgrandchildrenids[] = $person->ID;
		}
	}

	foreach($relationship as $rkey => $rvalue) {
		if(is_object($mainPerson)) {
			$mainPerson->{$rkey} = $rvalue;
		} elseif(is_array($mainPerson)) {
			$mainPerson["{$rkey}"] = $rvalue;
		}
	}

	if($type=='array') {
		return $relationship;
	}
	return $mainPerson;
}

function genealogy_get_relationships($mainPerson, $type = 'array', $family = false) {
	$family = genealogy_get_family();

	$family = get_relationships_worker($mainPerson, $family, gettype($mainPerson));
	if(empty($family)) { return false; }
	return $family;
}

function genealogy_get_person($person) {
	if(!isset($person->ID)) { return $person; }
	$famData = get_post_meta($person->ID, '_family_member_data', true);
	if(!is_array($famData)) { return $person; }
	foreach($famData as $keyData => $valueData) {
		$person->{$keyData} = $valueData;
	}
	return $person;
}

function genealogy_get_family($post = false) {
	// We want to exclude self from family information
	if($post) {
		$family = get_posts(array('post_type'=>'family', 'exclude' => array($post->ID), 'numberposts'=> 5000));
	} else {
		$family = get_posts(array('post_type'=>'family', 'numberposts'=> 5000));
	}
	foreach($family as $key=>$fam) {
#		$family[$key] = genealogy_get_person($fam->ID);
		$famData = get_post_meta($fam->ID, '_family_member_data', true);
		if(!is_array($famData)) { continue; }
		foreach($famData as $keyData => $valueData) {
			$family[$key]->{$keyData} = $valueData;
		}
	}
	return $family;
}

function genealogy_dropdown_list($list = array(), $data = null, $selector = 0, $dontselect = false) {

	global $post,$alt;
	if(!is_array($list)) { return false; }
	$options = '';
	foreach($list as $person) {
		// Kids aren't selectable; you only select parents & spouses
		if(isset($person->mother) && $person->mother == $post->ID || isset($person->father) && $person->father == $post->ID) { continue; }
		$first_name = $middle_name = $last_name = false;
		$first_name = isset($person->first_name) ? $person->first_name : false;
		$middle_name = isset($person->middle_name) ? $person->middle_name : false;
		$maiden_name = isset($person->maiden_name) ? $person->maiden_name : false;
		$last_name = isset($person->first_name) ? $person->last_name : false;
		if($maiden_name) { $middle_name = ''; $last_name = $maiden_name; }
		$name = (!empty($first_name) || !empty($middle_name) || !empty($last_name)) ? $first_name .' '.$middle_name.' '.$last_name : $person->post_title;
		$birth = !empty($person->birth_date) ? ' (Born '.$person->birth_date.')' : '';
		$selected = (isset($data["$selector"]) && $data["$selector"] == $person->ID && !$dontselect) ? ' selected="selected"' : '';
		$options .=  '<option value="'.$person->ID.'"'.$selected.'>'.$name.$birth.'</option>';
	}
	return $options;
}

function genealogy_make_select($family = array(), $data = null, $selector = 0, $timeframe = false, $MetaPostName = false) {
	global $pagenow;
	$men = $women = array();
	foreach($family as $key => $member) {
		if(isset($member->sex) && $member->sex == 'female') {
			$women[] = $member;
		} elseif($member->sex == 'male') {
			$men[] = $member;
		}
	}
	if(preg_match('/(husband|father)/ism', $selector)) {
		$list = $men;
	} elseif(preg_match('/(mother|wife)/ism', $selector)) {
		$list = $women;
	}


	if(empty($list)) { return ''; }
	$selectorName = preg_replace('/(\_[0-9]+)?/ism', '', $selector);
	#$title = '<h4>'.ucwords($selectorName).'</h4>';
	$selectName = 'family_member_data['.$selector.']';
//	foreach ( $post_data['meta'] as $key => $value )
//			update_meta( $key, $value['key'], $value['value'] );

	$select = '
	<label for="family_member_data_'.$selector.'" class="'.$selector.'">';
	if($pagenow !== 'edit.php') { $select .= '<span class="alignleft">'.ucwords($selector).'</span>'; }
	$select .= '
		<select id="family_member_data_'.$selector.'" name="'.$selectName.'" class="'.$selector.'">
			<option value="">Select a '.ucwords($selectorName).'</option>';
			if(isset($data["$selector"]) && $data["$selector"] == '-1') {
				$select .= '<option value="-1" selected="selected">Unknown</option>';
			} else {
				$select .= '<option value="-1">Unknown</option>';
			}
			$select .= genealogy_dropdown_list($list, $data, $selector,$MetaPostName);
	$select .= '</select></label>';

	$output = $select;

	if($timeframe) {
		$checked = !empty($data[$selector.'_timespan_checked']) ? ' checked="checked"' : '';
		$selected = ' selected="selected"';
		$output .= '<label for="family_member_data_'.$selector.'_timespan_checked" class="timespan_checked"><input id="family_member_data_'.$selector.'_timespan_checked" class="checkbox" type="checkbox" name="family_member_data['.$selector.'_timespan_checked]" value="1"'.$checked.' /> Enter Dates</label>';
		$timeframe = 'span';
		$output .= '<div class="timespan family_member_data_'.$selector.'_timespan_checked" class="timespan">';
		$id = sanitize_title('family_member_data_'.$selector.'_timespan_from');
		$data[$selector.'_timespan_from'] = isset($data[$selector.'_timespan_from']) ? $data[$selector.'_timespan_from'] : '';
		$output .= 'From <label for="'.$id.'"><input type="text" class="datepicker" id="'.$id.'" name="family_member_data['.$selector.'_timespan_from]" value="'.$data[$selector.'_timespan_from'].'" /></label>';
		$data[$selector.'_timespan_to'] = isset($data[$selector.'_timespan_to']) ? $data[$selector.'_timespan_to'] : '';
		$id = sanitize_title('family_member_data_'.$selector.'_timespan_to');
		$output .= ' to <label for="'.$id.'" class="timespan_to"><input type="text" class="datepicker" id="'.$id.'" name="family_member_data['.$selector.'_timespan_to]" value="'.$data[$selector.'_timespan_to'].'" /><span class="description leaveblank">Leave blank if current or unknown.</span></label>';
		$id = sanitize_title('family_member_data_'.$selector.'_timespan_reason');
		$output .= '<select name="family_member_data['.$selector.'_timespan_reason]" id="'.$id.'" class="timespan_reason">
						<option value=""'; if(!isset($data[$selector.'_timespan_reason']) || empty($data[$selector.'_timespan_reason'])) { $output .= $selected; }
						$output .= '>Select a Reason</option>
						<option value="divorce"'; if(isset($data[$selector.'_timespan_reason']) && $data[$selector.'_timespan_reason'] == 'divorce') { $output .= $selected; }
						$output .= '>Divorce</option>
						<option value="death"'; if(isset($data[$selector.'_timespan_reason']) && $data[$selector.'_timespan_reason'] == 'death') { $output .= $selected; }
						$output .= '>Death</option>
						<option value="other"'; if(isset($data[$selector.'_timespan_reason']) && $data[$selector.'_timespan_reason'] == 'other') { $output .= $selected; }
						$output .= '>Other</option>
					</select>';

		$output .= '</div>';
		if(empty($alt)) { $alt = ''; } else { $alt = '';}
		$title = isset($title) ? $title : '';
		$output = '<div class="clone'.$alt.'" rel="'.$selectorName.'">'.$title.' <span class="add_remove"><span class="genealogy_add ir" title="Insert Another">+</span><span class="hide-if-js"> | </span><span class="genealogy_remove ir" title="Remove">-</span></span>'.$output.'</div>';
	} else {
		$title = isset($title) ? $title : '';
		$output = isset($output) ? $output : '';
		$output = $title.$output;
	}

	return $output;
}

function genealogy_make_text($name=null, $value = null, $label = null, $data = array(), $class = 'text', $notes = '') {
	global $alt;
	if(empty($label)) { $label = ucwords($value); }
	if(isset($data[$name])) { $value = $data[$name]; }
	if(!empty($notes))  { $notes = "<span class='howto'>$notes</span>"; }
	$selectorName = preg_replace('/(\_[0-9]+)?/ism', '', $name);
	$id = sanitize_title('family_member_data_'.$name);
	if(empty($alt)) { $alt = ''; } else { $alt = '';}
	if(preg_match('/clone/ism', $class)) {
		$content = '<div class="clone'.$alt.'" rel="'.$selectorName.'"><label for="'.$id.'"><input type="text" id="'.$id.'" name="family_member_data['.$name.']" class="'.$class.' text" value="'.$value.'" /> <span class="input-label">'.$label.'</span> <span class="add_remove"><span class="genealogy_add ir" title="Insert Another">+</span><span class="hide-if-js"> | </span><span class="genealogy_remove ir" title="Remove">-</span></span></label>'.$notes.'</div>';
	} else {
		$content = isset($content) ? $content : '';
		$content .= '<div class="block"><label for="'.$id.'"><input type="text" id="'.$id.'" name="family_member_data['.$name.']" class="'.$class.' text" value="'.$value.'" /> <span class="input-label">'.$label.'</span> </label> '.$notes.'</div>';
	}
	return "$content";
}

function genealogy_make_radio($name=null, $value = null, $checked = false, $default = false) {
	if(is_array($checked)) {
		if(isset($checked[$name]) && $checked[$name] === $value) { $checked = true; } else { $checked = false; }
	}
	if(!is_array($checked) && $default) {
		$checked = true;
	}

	if(empty($label)) { $label = ucwords($value); }
	$checked = $checked ? ' checked="checked"' : '';
	$id = sanitize_title($name.'_'.$value);
	return '<label for="'.$id.'" class="'.$name.' '.$id.'"><input type="radio" class="radio" id="'.$id.'" name="family_member_data['.$name.']" value="'.$value.'"'.$checked.' /> '.$label.'<span></span></label>';
}

function genealogy_add_meta_box() {
	add_meta_box(	'family-member-data-div', __('Member Data', 'genealogy'),  'genealogy_member_data_metabox', 'family', 'normal', 'high');
}


function genealogy_add_new_admin_columns($columns) {

	$new_columns['cb'] = '<input type="checkbox" />';
	$new_columns['title'] = _x('Family Member', 'column name');
	$new_columns['id'] = __('ID', 'genealogy');
	$new_columns['images'] = __('Picture', 'genealogy');
	$new_columns['father'] = __('Father', 'genealogy');
	$new_columns['mother'] = __('Mother', 'genealogy');
	$new_columns['husband'] = __('Husband', 'genealogy');
	$new_columns['wife'] = __('Wife', 'genealogy');
	$new_columns['birth_date'] = __('Birth', 'genealogy');
	$new_columns['death_date'] = __('Death', 'genealogy');

	#return array_merge($columns, $new_columns);
	return $new_columns;
}

function genealogy_manage_family_admin_columns($columns) {
	$columns['title'] = __('Family Member', 'genealogy');
	$columns['father'] = __('Father', 'genealogy');
	$columns['mother'] = __('Mother', 'genealogy');
	$columns['husband'] = __('Husband', 'genealogy');
	$columns['wife'] = __('Wife', 'genealogy');
	$columns['birth_date'] = __('Birth Date', 'genealogy');
	$columns['death_date'] = __('Date of Death', 'genealogy');
	return $columns;
}
function genealogy_manage_admin_columns($column_name, $id) {
	global $wpdb,$post;

	$data = get_post_meta($post->ID, '_family_member_data', true);
	$family = genealogy_get_family($post);

	switch ($column_name) {
	case 'id':
		echo $id;
	        break;
	case 'father':
	case 'mother':
	case 'husband':
	case 'wife':
		if(empty($data["$column_name"])) { break; }
		$person = get_post($data["$column_name"]);
		if(is_object($person)) {
			echo '<a href="'.get_permalink($data["$column_name"]).'">'.$person->post_title.'</a>';
		}
		break;
	case 'images':
		// Get number of images in gallery
		if(function_exists('get_the_post_thumbnail')) {
			echo get_the_post_thumbnail($id, array(100,100));
		}
		break;
	case 'death_date':
	case 'birth_date':
	default:
		if(empty($data["$column_name"])) { break; }
		echo $data["$column_name"];
		break;
	} // end switch
}
