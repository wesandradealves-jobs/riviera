<?php

@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );
require_once('class-tgm-plugin-activation.php');

global $post;

function remove_menus(){
    global $post;
    remove_menu_page( 'index.php' );                  //Dashboard
    remove_menu_page( 'jetpack' );                    //Jetpack*
    remove_menu_page( 'edit.php' );                   //Posts
    if(!current_user_can('administrator')){
        remove_menu_page( 'upload.php' );                 //Media
        remove_menu_page( 'edit.php?post_type=page' );    //Pages
        remove_menu_page( 'edit-comments.php' );          //Comments
        remove_menu_page( 'themes.php' );                 //Appearance
        remove_menu_page( 'plugins.php' );                //Plugins
        remove_menu_page( 'users.php' );                  //Users
        remove_menu_page( 'tools.php' );                  //Tools
        remove_menu_page( 'options-general.php' );        //Settings
    }
}
////////////////////////////////////////////////////
function attach_template_to_page( $page_name, $template_file_name ) {

    // Look for the page by the specified title. Set the ID to -1 if it doesn't exist.
    // Otherwise, set it to the page's ID.
    $page = get_page_by_title( $page_name, OBJECT, 'page' );
    $page_id = null == $page ? -1 : $page->ID;

    // Only attach the template if the page exists
    if( -1 != $page_id ) {
        update_post_meta( $page_id, '_wp_page_template', $template_file_name );
    } // end if

    return $page_id;

} // end attach_template_to_page
////////////////////////////////////////////////////
function query_post_type($query) {
    if(is_category() || is_tag()) {
    $post_type = get_query_var('post_type');
    if($post_type)
    $post_type = $post_type;
    else
    $post_type = array('nav_menu_item','post','articles');
    $query->set('post_type',$post_type);
    return $query;
    }
    }
////////////////////////////////////////////////////    
function customizer( $wp_customize ) {
    $wp_customize->add_panel( 'customization', array(
        'priority'       => 2,
        'title'          => __('Customização')
    ));  
    $wp_customize->add_section( 'footer' , array(
        'title'       => __( 'Rodapé (Contato)' ),
        'panel' => 'customization',
        'priority'    => 1
        ));   
        $wp_customize->add_setting( 'email' );
        $wp_customize->add_control('email',  array(
            'label' => 'E-mail',
            'section' => 'footer',
            'type' => 'email',
            'settings' => 'email'
        ));    
        $wp_customize->add_setting( 'telefone' );
        $wp_customize->add_control('telefone',  array(
            'label' => 'Telefone',
            'section' => 'footer',
            'type' => 'text',
            'settings' => 'telefone'
        ));            
        // Header
        $wp_customize->add_section( 'header' , array(
        'title'       => __( 'Header' ),
        'panel' => 'customization',
        'priority'    => 1
        ));           
        $wp_customize->add_setting( 'logo' );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo', array(
        'label'    => __( 'Logo' ),
        'section'  => 'header',
        'settings' => 'logo'
        )));        
}
////////////////////////////////////////////////////
function remove_customizer_settings( $wp_customize ){
    $wp_customize->remove_section('static_front_page');
}
////////////////////////////////////////////////////
function get_custom_field_data($key, $echo = false) {
$value = get_post_meta($post->ID, $key, true);
if($echo == false) {
return $value;
} else {
echo $value;
}
}
////////////////////////////////////////////////////
function menu() {
    register_nav_menus(
    array(
    // 'default' => __( 'Default' ),
    // 'copyright' => __( 'Copyright' )
    )
    );
    }
////////////////////////////////////////////////////
set_post_thumbnail_size( 600, 600 );
////////////////////////////////////////////////////
function regScripts() {
    wp_deregister_script('jquery');
    wp_enqueue_script('vendors', get_template_directory_uri()."/assets/js/vendors.js");
    wp_enqueue_script('commons', get_template_directory_uri()."/assets/js/commons.js");
    wp_enqueue_style('style', get_stylesheet_directory_uri().'/style.css');
}
////////////////////////////////////////////////////
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
////////////////////////////////////////////////////
function df_terms_clauses($clauses, $taxonomy, $args) {
    if (!empty($args['post_type'])) {
    global $wpdb;
    $post_types = array();
    foreach($args['post_type'] as $cpt) {
    $post_types[] = "'".$cpt."'";
    }
    if(!empty($post_types)) {
    $clauses['fields'] = 'DISTINCT '.str_replace('tt.*', 'tt.term_taxonomy_id, tt.term_id, tt.taxonomy, tt.description, tt.parent', $clauses['fields']).', COUNT(t.term_id) AS count';
    $clauses['join'] .= ' INNER JOIN '.$wpdb->term_relationships.' AS r ON r.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN '.$wpdb->posts.' AS p ON p.ID = r.object_id';
    $clauses['where'] .= ' AND p.post_type IN ('.implode(',', $post_types).')';
    $clauses['orderby'] = 'GROUP BY t.term_id '.$clauses['orderby'];
    }
    }
    return $clauses;
    }
////////////////////////////////////////////////////
function hide_editor() {
    $post_id = isset($_GET['post']) ? isset($_GET['post']) : isset($_POST['post_ID']);
    if( !isset( $post_id ) ) return;
    remove_post_type_support('page', 'editor');
    remove_post_type_support('page', 'excerpt');
    remove_post_type_support('page', 'author');
    remove_post_type_support('page', 'comments');
    remove_post_type_support('page', 'revisions');
    remove_post_type_support('page', 'custom-fields'); 
    remove_post_type_support('page', 'thumbnail');
    remove_post_type_support('page', 'page-attributes');    
}
////////////////////////////////////////////////////
function called( $file_name, $files = array(), $dir = '' ) {

    if( empty( $files ) ) {
        $files = debug_backtrace();
    }

    if( ! $dir ) {
        $dir = get_stylesheet_directory() . '/';
    }

    $dir = str_replace( "/", "\\", $dir );
    $caller_theme_file = array();

    foreach( $files as $file ) {
        if( false !== mb_strpos($file['file'], $dir) ) {
            $caller_theme_file[] = $file['file'];
        }
    }

    if( $file_name ) {
        return in_array( $dir . $file_name, $caller_theme_file );
    }

    return;

}
////////////////////////////////////////////////////
function sanitize( $input, $setting ) {
    global $wp_customize;
 
    $control = $wp_customize->get_control( $setting->id );
 
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}
////////////////////////////////////////////////////
function pagination(){
    echo '
    <ul class="post-pagination '.(is_single() ? '-single' : '').'">';
        if(is_single()) :
            if(get_previous_post()->ID) :
                echo '
                <li class="-actions -prev">
                    <a class="prev" href="'.esc_url( get_permalink( get_previous_post()->ID ) ).'" title="Post Anterior"><i class="fa fa-chevron-left"></i> Post Anterior</a>
                </li>';
            endif; 
            if(get_next_post()->ID) :
                echo '
                <li class="-actions -next">
                    <a class="next" href="'.esc_url( get_permalink( get_next_post()->ID ) ).'" title="Próximo Post">Próximo Post <i class="fa fa-chevron-right"></i></a>
                </li>';
            endif; 
        else : 
            if(get_previous_posts_link()) :
                echo '
                <li class="-actions -prev">
                    <a class="prev" href="'.get_previous_posts_page_link().'" title="Posts Anteriores"><i class="fa fa-chevron-left"></i> Post Anteriores</a>
                </li>';
            endif; 
            if(get_next_posts_link()) :
                echo '
                <li class="-actions -next">
                    <a class="next" href="'.get_next_posts_page_link().'" title="Próximos Posts">Próximos Posts <i class="fa fa-chevron-right"></i></a>
                </li>';
            endif; 
        endif;
    echo '</ul>';
}
////////////////////////////////////////////////////
function the_slug_exists($post_name) {
	global $wpdb;
	if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
		return true;
	} else {
		return false;
	}
}
if (isset($_GET['activated']) && is_admin()){
    $home_page_title = 'Home';
    $home_page_content = '';
    $home_page_check = get_page_by_title($home_page_title);
    $home_page = array(
	    'post_type' => 'page',
	    'post_title' => $home_page_title,
	    'post_content' => $home_page_content,
	    'post_status' => 'publish',
	    'post_author' => 1,
	    'ID' => 2,
	    'post_slug' => 'home'
    );
    if(!isset($home_page_check->ID) && !the_slug_exists('home')){
        $home_page_id = wp_insert_post($home_page);
    }
}
if (isset($_GET['activated']) && is_admin()){
	$front_page = 2; // this is the default page created by WordPress
	update_option( 'page_on_front', $front_page );
	update_option( 'show_on_front', 'page' );
}
////////////////////////////////////////////////////
$menuname = 'Navigation';
// $bpmenulocation = 'lblgbpmenu';
// Does the menu exist already?
$menu_exists = wp_get_nav_menu_object( $menuname );

// If it doesn't exist, let's create it.
if( !$menu_exists){
    $menu_id = wp_create_nav_menu($menuname);

    // Set up default BuddyPress links and add them to the menu.
    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Home'),
        'menu-item-classes' => 'home',
        'menu-item-url' => home_url( '/' ), 
        'menu-item-status' => 'publish'));

    // Grab the theme locations and assign our newly-created menu
    if( !has_nav_menu( $bpmenulocation ) ){
        $locations = get_theme_mod('nav_menu_locations');
        $locations[$bpmenulocation] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
}
////////////////////////////////////////////////////
if( function_exists('acf_add_options_page') ) {
 
    acf_add_options_page(array(
            'page_title' 	=> 'Site',
            'menu_title'	=> 'Página Inicial',
            'menu_slug' 	=> 'site',
            'capability'	=> 'edit_posts',
            'parent_slug'   => '',
            'icon_url'      => '', // Add this line and replace the second inverted commas with class of the icon you like
            'position' => -1
        ));
        
        // acf_add_options_sub_page(array(
        // 	'page_title' 	=> 'General Settings',
        // 	'menu_title'	=> 'General Settings',
        // 	'capability'	=> 'edit_posts',
            //         'parent_slug'   => 'site'
        // ));

        // acf_add_options_sub_page(array(
        //     'page_title' 	=> 'General Settings',
        //     'menu_title'	=> 'General',
        //     'capability'	=> 'edit_posts',
        //     'parent_slug'   => 'site'
        // )); 
        
        acf_add_options_sub_page(array(
            'page_title' 	=> '',
            'menu_title'	=> 'Home',
            'capability'	=> 'edit_posts',
            'parent_slug'   => 'site'
        ));
        
        // acf_add_options_sub_page(array(
        // 	'page_title' 	=> 'Product Page Settings',
        // 	'menu_title'	=> 'Product',
        // 	'parent_slug'	=> 'theme-product-settings',
        // 	'capability'	=> 'edit_posts',
            // 'parent_slug'   => 'flow',
        // ));
        
        // acf_add_options_sub_page(array(
        // 	'page_title' 	=> 'Why Flow Page Settings',
        // 	'menu_title'	=> 'Why Flow',
        // 	'parent_slug'	=> 'theme-product-settings',
        // 	'capability'	=> 'edit_posts',
            // 'parent_slug'   => 'flow',
        // ));
        
        // acf_add_options_sub_page(array(
        // 	'page_title' 	=> 'Company Page Settings',
        // 	'menu_title'	=> 'Company',
        // 	'parent_slug'	=> 'theme-product-settings',
        // 	'capability'	=> 'edit_posts',
            // 'parent_slug'   => 'flow',
        // ));
        
        // acf_add_options_sub_page(array(
        // 	'page_title' 	=> 'Careers Page Settings',
        // 	'menu_title'	=> 'Careers',
        // 	'parent_slug'	=> 'theme-product-settings',
        // 	'capability'	=> 'edit_posts',
            // 'parent_slug'   => 'flow',
        // ));
        
        // acf_add_options_sub_page(array(
        // 	'page_title' 	=> 'Request a Demo Page Settings',
        // 	'menu_title'	=> 'Request',
        // 	'parent_slug'	=> 'theme-request-settings',
        // 	'capability'	=> 'edit_posts',
            // 'parent_slug'   => 'flow',
        // ));
     
}
////////////////////////////////////////////////////
function register_required_plugins() {
    $plugins = array(
        array(
            'name' => 'acf-repeater',
            'slug' => 'acf-repeater', 
                    'source' => get_template_directory_uri() . '/plugins/acf-repeater.zip', 
                    'required' => true, 
                    'version' => '', 
                    'force_activation' => true, 
                    'force_deactivation' => false, 
                    'external_url' => '',
                ),
        array(
            'name' => 'acf-unique-id-field',
            'slug' => 'acf-unique-id-field', 
                    'source' => get_template_directory_uri() . '/plugins/acf-unique-id-field.zip', 
                    'required' => true, 
                    'version' => '', 
                    'force_activation' => true, 
                    'force_deactivation' => false, 
                    'external_url' => '',
                ),
        array(
            'name' => 'advanced-custom-fields-pro',
            'slug' => 'advanced-custom-fields-pro', 
                    'source' => get_template_directory_uri() . '/plugins/advanced-custom-fields-pro.zip', 
                    'required' => true, 
                    'version' => '', 
                    'force_activation' => true, 
                    'force_deactivation' => false, 
                    'external_url' => '',
                )
        );
            $config = array(
                'default_path' => '', // Default absolute path to pre-packaged plugins.
                'menu' => 'tgmpa-install-plugins', // Menu slug.
                'has_notices' => true, // Show admin notices or not.
                'dismissable' => true, // If false, a user cannot dismiss the nag message.
                'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
                'is_automatic' => false, // Automatically activate plugins after installation or not.
                'message' => '', // Message to output right before the plugins table.
                'strings' => array(
                    'page_title' => __( 'Install Required Plugins', 'tgmpa' ),
                'menu_title' => __( 'Install Plugins', 'tgmpa' ),
                'installing' => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
                'oops' => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
                'notice_can_install_required' => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
                'notice_can_install_recommended' => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
                'notice_cannot_install' => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
                'notice_can_activate_required' => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
                'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
                'notice_cannot_activate' => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
                'notice_ask_to_update' => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
                'notice_cannot_update' => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
                'install_link' => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
                'activate_link' => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
                'return' => __( 'Return to Required Plugins Installer', 'tgmpa' ),
                'plugin_activated' => __( 'Plugin activated successfully.', 'tgmpa' ),
                'complete' => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
                'nag_type' => 'updated' // Determines admin notice type – can only be 'updated', 'update-nag' or 'error'.
                )
        );
        tgmpa( $plugins, $config );
    }
////////////////////////////////////////////////////
/**
 * Replace # with js
 * @param string $menu_item item HTML
 * @return string item HTML
 */
function wpse_226884_replace_hash($menu_item) {
    if (strpos($menu_item, 'href="#"') !== false) {
        $menu_item = str_replace('href="#"', 'href="javascript:void(0);"', $menu_item);
    }
    return $menu_item;
}
////////////////////////////////////////////////////
function add_mobile_anchor( $items, $args )
{
    if($args->container_class == 'navigation')
    {
        $items .= '
            <li>
                <button onclick="mobileNavigation()" type="button" class="tcon tcon-menu--xbutterfly" aria-label="toggle menu">
                    <span class="tcon-menu__lines" aria-hidden="true"></span>
                    <span class="tcon-visuallyhidden">toggle menu</span>
                </button>
            </li>
        ';
    }

    return $items;
}
////////////////////////////////////////////////////
function cpt() {
    $labelsServicos = array(
        'name' => _x('O Que Fazemos', 'post type general name'),
        'singular_name' => _x('Serviço', 'post type singular name'),
        'add_new' => _x('Add novo', 'serviço'),
        'add_new_item' => __('Add novo serviço'),
        'edit_item' => __('Editar serviço'),
        'new_item' => __('Novo serviço'),
        'view_item' => __('Ver serviço'),
        'search_items' => __('Buscar serviços'),
        'not_found' =>  __('Nada encontrado'),
        'not_found_in_trash' => __('Nada encontrado'),
        'parent_item_colon' => ''
    );
    $argsServicos = array(
        'labels' => $labelsServicos,
        'public' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 8,
        'supports' => array('title', 'thumbnail', 'excerpt')
    ); 

    $labelsServicosTax = array(
        'name'              => _x( 'Categorias', 'taxonomy general name' ),
        'singular_name'     => _x( 'Categoria', 'taxonomy singular name' ),
        'search_items'      => __( 'Buscar Categorias' ),
        'all_items'         => __( 'Todas as Categorias' ),
        'parent_item'       => __( 'Categoria Pai' ),
        'parent_item_colon' => __( 'Categoria Pai:' ),
        'edit_item'         => __( 'Editar categoria' ),
        'update_item'       => __( 'Atualizar categoria' ),
        'add_new_item'      => __( 'Adicionar nova categoria' ),
        'new_item_name'     => __( 'Novo nome' ),
        'menu_name'         => __( 'Categorias' ),
    );
    $argsServicosTax = array(
        'hierarchical'      => true, // Set this to 'false' for non-hierarchical taxonomy (like tags)
        'labels'            => $labelsServicosTax,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'categories' ),
    );
    register_post_type('servicos', $argsServicos);
    register_taxonomy( 'servicos_categories', array( 'servicos' ), $argsServicosTax );
}
////////////////////////////////////////////////////
attach_template_to_page( 'home', 'page-templates/home.php' );
add_theme_support( 'post-thumbnails' );
if(!current_user_can('administrator')){
    add_filter('acf/settings/show_admin', '__return_false');
}
add_filter( 'wp_nav_menu_items', 'add_mobile_anchor', 10, 2);
add_filter('walker_nav_menu_start_el', 'wpse_226884_replace_hash', 999);
add_filter('upload_mimes', 'cc_mime_types');
add_filter( 'wpcf7_validate_configuration', '__return_false' );
add_filter('pre_get_posts', 'query_post_type');
add_filter('terms_clauses', 'df_terms_clauses', 10, 3);
add_action( 'wp_enqueue_scripts', 'regScripts' );
add_action( 'init', 'menu' );
add_action( 'admin_init', 'hide_editor' );
add_action( 'customize_register', 'remove_customizer_settings', 20 );
add_action( 'customize_register', 'customizer' );
add_action( 'admin_menu', 'remove_menus' );
add_action( 'tgmpa_register', 'register_required_plugins');
add_action( 'init', 'cpt');
?>