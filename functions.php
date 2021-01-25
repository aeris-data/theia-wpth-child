<?php

add_action( 'wp_enqueue_scripts', 'theia_wpthchild_enqueue_styles' );
function theia_wpthchild_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );


}
add_theme_support( 'post-thumbnails' );
/* 
* LOAD TEXT DOMAIN FOR TRANSLATION
*/

function theia_wpthchild_load_theme_textdomain() {
    load_child_theme_textdomain( 'theia_wpthchild_aeris-wordpress-theme', get_stylesheet_directory() . '/languages' );
    }
    add_action( 'after_setup_theme', 'theia_wpthchild_load_theme_textdomain' );


/* Autoriser l'upload de format zip dans les médias */
// GESTION DES FORMATS DE FICHIERS DE LA MÉDIATHÈQUE
 
// Appel de Extend_Upload_Mimes sur le tableau des mimes
// supportés :
add_filter('upload_mimes', 'theia_wpthchild_Extend_Upload_Mimes');
/* 
 * Fonction Extend_Upload_Mimes :
 * Prend en argument le tableau associatif des types mimes
 * supportés le modifie et le retourne modifié.
 */
function theia_wpthchild_Extend_Upload_Mimes ( $CurrentMimes=array() ) {
//	Ajout de nouveaux types :
		$CurrentMimes['zip'] = 'application/zip';
 
//	Suppression de types non souhaités :
	unset( $CurrentMimes['exe'] );
 
	return $CurrentMimes;
}

/**
 * Ajout des categories aux pages
 */

function theia_wpthchild_add_taxonomies_to_pages() {

    register_taxonomy_for_object_type( 'category', 'page' );
} 
add_action( 'init', 'theia_wpthchild_add_taxonomies_to_pages' );

/**
 * Enqueue Javascript files sur template theia
 */
function theia_wpthchild_load_javascript_files() {
	if ( is_singular('ces') || is_singular('products') || is_singular('art') || is_page_template('template-thema.php') || is_page_template('template-produits.php')) {
		wp_enqueue_script('theme_aeris_jquery_sticky', get_template_directory_uri() . '/js/jquery.sticky.js', array('jquery'), '', false );
		wp_enqueue_script('theme_aeris_toc', get_template_directory_uri() . '/js/toc.js', array('jquery'), '', false );
	}
}
add_action( 'wp_enqueue_scripts', 'theia_wpthchild_load_javascript_files' );


/**
 * Affichage des contenus associés
 * 
 */

function theia_wpthchild_get_associate_content($parameters, $args) {
   
    $the_query = new WP_Query( $args );
    
    // The Loop
    if ( $the_query->have_posts() ) {

        echo '<section><h3>'.__( $parameters['sectionTitle'], 'theia_wpthchild_aeris-wordpress-theme' ).'</h3>';
        echo '<ul>';
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
             
            // $titleItem=mb_strimwidth(get_the_title(), 0, 100, '...');  
            ?>
                <li>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_title(); ?>
                    </a>
                </li>
            <?php
        }
        echo '</ul></section>';
        /* Restore original Post Data */
        wp_reset_postdata();
    } else {
        // no posts found
    }
}

/***
 * Ajout de l'excerpt pour les pages
 */

add_post_type_support( 'page', 'excerpt' );

/* ------------------------------------------------------------------------------------------------- */
/**
 * CUSTOM TAXONOMIES
 */

if ( ! function_exists( 'theia_wpthchild_custom_taxonomy' ) ) {
// Register Custom Taxonomy
function theia_wpthchild_custom_taxonomy() {

    /**
     * THEMES (Research & expertise)
    */

    $labelsTheme = array(
        'name'                       => _x( 'Themes', 'Taxonomy General Name', 'aeris-wordpress-theme' ),
        'singular_name'              => _x( 'Theme', 'Taxonomy Singular Name', 'aeris-wordpress-theme' ),
        'menu_name'                  => __( 'Theme', 'aeris-wordpress-theme' ),
        'all_items'                  => __( 'All themes', 'aeris-wordpress-theme' ),
        'parent_item'                => __( 'Parent theme', 'aeris-wordpress-theme' ),
        'parent_item_colon'          => __( 'Parent theme:', 'aeris-wordpress-theme' ),
        'new_item_name'              => __( 'New theme', 'aeris-wordpress-theme' ),
        'add_new_item'               => __( 'Add New theme', 'aeris-wordpress-theme' ),
        'edit_item'                  => __( 'Edit theme', 'aeris-wordpress-theme' ),
        'update_item'                => __( 'Update theme', 'aeris-wordpress-theme' ),
        'view_item'                  => __( 'View theme', 'aeris-wordpress-theme' ),
        'separate_items_with_commas' => __( 'Separate themes with commas', 'aeris-wordpress-theme' ),
        'add_or_remove_items'        => __( 'Add or remove themes', 'aeris-wordpress-theme' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'aeris-wordpress-theme' ),
        'popular_items'              => __( 'Popular themes', 'aeris-wordpress-theme' ),
        'search_items'               => __( 'Search themes', 'aeris-wordpress-theme' ),
        'not_found'                  => __( 'Not Found', 'aeris-wordpress-theme' ),
        'no_terms'                   => __( 'No theme', 'aeris-wordpress-theme' ),
        'items_list'                 => __( 'Themes list', 'aeris-wordpress-theme' ),
        'items_list_navigation'      => __( 'Themes list navigation', 'aeris-wordpress-theme' ),
    );
    $rewriteTheme = array(
        'slug'                       => 'theme',
        'with_front'                 => true,
        'hierarchical'               => false,
    );
    $argsTheme = array(
        'labels'                     => $labelsTheme,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'                    => $rewriteTheme,
        'show_in_rest'               => true,
    );
    register_taxonomy( 'theme', array( 'page' ), $argsTheme );
    register_taxonomy_for_object_type( 'theme', 'post' );

    /**
     * CES
    */

    $labelsCESTag = array(
        'name'                       => _x( 'CES Tags', 'Taxonomy General Name', 'aeris-wordpress-theme' ),
        'singular_name'              => _x( 'CES Tag', 'Taxonomy Singular Name', 'aeris-wordpress-theme' ),
        'menu_name'                  => __( 'CES Tags', 'aeris-wordpress-theme' ),
        'all_items'                  => __( 'All CES Tag', 'aeris-wordpress-theme' ),
        'parent_item'                => __( 'Parent CES Tag', 'aeris-wordpress-theme' ),
        'parent_item_colon'          => __( 'Parent CES Tag:', 'aeris-wordpress-theme' ),
        'new_item_name'              => __( 'New CES Tag', 'aeris-wordpress-theme' ),
        'add_new_item'               => __( 'Add New CES Tag', 'aeris-wordpress-theme' ),
        'edit_item'                  => __( 'Edit CES Tag', 'aeris-wordpress-theme' ),
        'update_item'                => __( 'Update CES Tag', 'aeris-wordpress-theme' ),
        'view_item'                  => __( 'View theme', 'aeris-wordpress-theme' ),
        'separate_items_with_commas' => __( 'Separate CES Tag with commas', 'aeris-wordpress-theme' ),
        'add_or_remove_items'        => __( 'Add or remove CES Tag', 'aeris-wordpress-theme' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'aeris-wordpress-theme' ),
        'popular_items'              => __( 'Popular CES Tag', 'aeris-wordpress-theme' ),
        'search_items'               => __( 'Search CES Tag', 'aeris-wordpress-theme' ),
        'not_found'                  => __( 'Not Found', 'aeris-wordpress-theme' ),
        'no_terms'                   => __( 'No CES Tag', 'aeris-wordpress-theme' ),
        'items_list'                 => __( 'CES Tag list', 'aeris-wordpress-theme' ),
        'items_list_navigation'      => __( 'CES Tag list navigation', 'aeris-wordpress-theme' ),
    );
    $rewriteCESTag = array(
        'slug'                       => 'ces',
        'with_front'                 => true,
        'hierarchical'               => false,
    );
    $argsCESTag = array(
        'labels'                     => $labelsCESTag,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'                    => $rewriteCESTag,
        'show_in_rest'               => true,
    );
    register_taxonomy( 'cestag', array( 'page' ), $argsCESTag );
    register_taxonomy_for_object_type( 'cestag', 'post' );
    register_taxonomy_for_object_type( 'cestag', 'ces' );
    register_taxonomy_for_object_type( 'cestag', 'products' );

    /**
     * ART
    */
    $labelsART = array(
        'name'                       => _x( 'ART Tags', 'Taxonomy General Name', 'aeris-wordpress-theme' ),
        'singular_name'              => _x( 'ART Tag', 'Taxonomy Singular Name', 'aeris-wordpress-theme' ),
        'menu_name'                  => __( 'ART Tags', 'aeris-wordpress-theme' ),
        'all_items'                  => __( 'All types', 'aeris-wordpress-theme' ),
        'parent_item'                => __( 'Parent type', 'aeris-wordpress-theme' ),
        'parent_item_colon'          => __( 'Parent type:', 'aeris-wordpress-theme' ),
        'new_item_name'              => __( 'New type', 'aeris-wordpress-theme' ),
        'add_new_item'               => __( 'Add New type', 'aeris-wordpress-theme' ),
        'edit_item'                  => __( 'Edit type', 'aeris-wordpress-theme' ),
        'update_item'                => __( 'Update type', 'aeris-wordpress-theme' ),
        'view_item'                  => __( 'View type', 'aeris-wordpress-theme' ),
        'separate_items_with_commas' => __( 'Separate types with commas', 'aeris-wordpress-theme' ),
        'add_or_remove_items'        => __( 'Add or remove types', 'aeris-wordpress-theme' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'aeris-wordpress-theme' ),
        'popular_items'              => __( 'Popular types', 'aeris-wordpress-theme' ),
        'search_items'               => __( 'Search types', 'aeris-wordpress-theme' ),
        'not_found'                  => __( 'Not Found', 'aeris-wordpress-theme' ),
        'no_terms'                   => __( 'No type', 'aeris-wordpress-theme' ),
        'items_list'                 => __( 'ART list', 'aeris-wordpress-theme' ),
        'items_list_navigation'      => __( 'ART list navigation', 'aeris-wordpress-theme' ),
    );
    $rewriteART = array(
        'slug'                       => 'art',
        'with_front'                 => true,
        'hierarchical'               => false,
    );
    $argsART = array(
        'labels'                     => $labelsART,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'                    => $rewriteART,
        'show_in_rest'               => true,
    );
    register_taxonomy( 'arttag', array( 'art' ), $argsART );
    register_taxonomy_for_object_type( 'arttag', 'post' );
    register_taxonomy_for_object_type( 'arttag', 'page' );
    
    /**
     * Type de produit
    */
    $labelsProducts = array(
        'name'                       => _x( 'Types of products', 'Taxonomy General Name', 'aeris-wordpress-theme' ),
        'singular_name'              => _x( 'Type of products', 'Taxonomy Singular Name', 'aeris-wordpress-theme' ),
        'menu_name'                  => __( 'Types of products', 'aeris-wordpress-theme' ),
        'all_items'                  => __( 'All types', 'aeris-wordpress-theme' ),
        'parent_item'                => __( 'Parent type', 'aeris-wordpress-theme' ),
        'parent_item_colon'          => __( 'Parent type:', 'aeris-wordpress-theme' ),
        'new_item_name'              => __( 'New type', 'aeris-wordpress-theme' ),
        'add_new_item'               => __( 'Add New type', 'aeris-wordpress-theme' ),
        'edit_item'                  => __( 'Edit type', 'aeris-wordpress-theme' ),
        'update_item'                => __( 'Update type', 'aeris-wordpress-theme' ),
        'view_item'                  => __( 'View type', 'aeris-wordpress-theme' ),
        'separate_items_with_commas' => __( 'Separate types with commas', 'aeris-wordpress-theme' ),
        'add_or_remove_items'        => __( 'Add or remove types', 'aeris-wordpress-theme' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'aeris-wordpress-theme' ),
        'popular_items'              => __( 'Popular types', 'aeris-wordpress-theme' ),
        'search_items'               => __( 'Search types', 'aeris-wordpress-theme' ),
        'not_found'                  => __( 'Not Found', 'aeris-wordpress-theme' ),
        'no_terms'                   => __( 'No type', 'aeris-wordpress-theme' ),
        'items_list'                 => __( 'Type of products list', 'aeris-wordpress-theme' ),
        'items_list_navigation'      => __( 'Type of products list navigation', 'aeris-wordpress-theme' ),
    );
    $rewriteProducts = array(
        'slug'                       => 'typeofproduct',
        'with_front'                 => true,
        'hierarchical'               => false,
    );
    $argsProducts = array(
        'labels'                     => $labelsProducts,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'                    => $rewriteProducts,
        'show_in_rest'               => true,
    );
    register_taxonomy( 'typeproduct', array( 'products' ), $argsProducts );
    register_taxonomy_for_object_type( 'typeproduct', 'page' );

}
add_action( 'init', 'theia_wpthchild_custom_taxonomy', 0 );

}

/******************************************************************
 * Afficher les archives des custom taxonomies
 * $categories = get_the_terms( $post->ID, 'category');  
 */

function theia_wpthchild_show_categories($categories, $slugRewrite) {
 
    if( $categories ) {
    ?>
    <div class="tag">
    <?php
        foreach( $categories as $categorie ) { 
            if ($categorie->slug !== "non-classe") {
                if ( "en" == pll_current_language()) {
                    echo '<a href="'.site_url().'/'.pll_current_language().'/'.$slugRewrite.'/'.$categorie->slug.'" class="'.$categorie->slug.'">';
                } else {
                    echo '<a href="'.site_url().'/'.$slugRewrite.'/'.$categorie->slug.'" class="'.$categorie->slug.'">';
                }
                echo $categorie->name; 
                ?>                    
            </a>
    <?php 
            }
        }
    ?>
    </div>
  <?php
      } 
  }

/* ------------------------------------------------------------------------------------------------- */
/**
 * CUSTOM POSTS
 */

if ( ! function_exists('theia_wpthchild_custom_post') ) {

    // Register Custom Post Type
    function theia_wpthchild_custom_post() {

        //
        // CUSTOM POST CES 
        //

        $labelsCES = array(
            'name'                  => _x( 'CES', 'Post Type General Name', 'aeris-wordpress-theme' ),
            'singular_name'         => _x( 'CES', 'Post Type Singular Name', 'aeris-wordpress-theme' ),
            'menu_name'             => __( 'CES', 'aeris-wordpress-theme' ),
            'name_admin_bar'        => __( 'CES', 'aeris-wordpress-theme' ),
            'archives'              => __( 'Item Archives', 'aeris-wordpress-theme' ),
            'attributes'            => __( 'Item Attributes', 'aeris-wordpress-theme' ),
            'parent_item_colon'     => __( 'Parent Item:', 'aeris-wordpress-theme' ),
            'all_items'             => __( 'All Items', 'aeris-wordpress-theme' ),
            'add_new_item'          => __( 'Add New Item', 'aeris-wordpress-theme' ),
            'add_new'               => __( 'Add New', 'aeris-wordpress-theme' ),
            'new_item'              => __( 'New Item', 'aeris-wordpress-theme' ),
            'edit_item'             => __( 'Edit Item', 'aeris-wordpress-theme' ),
            'update_item'           => __( 'Update Item', 'aeris-wordpress-theme' ),
            'view_item'             => __( 'View Item', 'aeris-wordpress-theme' ),
            'view_items'            => __( 'View Items', 'aeris-wordpress-theme' ),
            'search_items'          => __( 'Search Item', 'aeris-wordpress-theme' ),
            'not_found'             => __( 'Not found', 'aeris-wordpress-theme' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'aeris-wordpress-theme' ),
            'featured_image'        => __( 'Featured Image', 'aeris-wordpress-theme' ),
            'set_featured_image'    => __( 'Set featured image', 'aeris-wordpress-theme' ),
            'remove_featured_image' => __( 'Remove featured image', 'aeris-wordpress-theme' ),
            'use_featured_image'    => __( 'Use as featured image', 'aeris-wordpress-theme' ),
            'insert_into_item'      => __( 'Insert into item', 'aeris-wordpress-theme' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'aeris-wordpress-theme' ),
            'items_list'            => __( 'Items list', 'aeris-wordpress-theme' ),
            'items_list_navigation' => __( 'Items list navigation', 'aeris-wordpress-theme' ),
            'filter_items_list'     => __( 'Filter items list', 'aeris-wordpress-theme' ),
        );
        $rewriteCES = array(
            'slug'                  => 'ceslist',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $argsCES = array(
            'label'                 => __( 'CES', 'aeris-wordpress-theme' ),
            'description'           => __( 'Post Type Description', 'aeris-wordpress-theme' ),
            'labels'                => $labelsCES,
            'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
            'taxonomies'            => array( 'category', 'theme', 'cestag' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 20,
            'menu_icon'             => 'dashicons-awards',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewriteCES,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
        );
        register_post_type( 'ces', $argsCES );

        //
        // CUSTOM POST PRODUCTS 
        //

        $labelsProducts = array(
            'name'                  => _x( 'Data & Products', 'Post Type General Name', 'aeris-wordpress-theme' ),
            'singular_name'         => _x( 'Data & Product', 'Post Type Singular Name', 'aeris-wordpress-theme' ),
            'menu_name'             => __( 'Data & Products', 'aeris-wordpress-theme' ),
            'name_admin_bar'        => __( 'Data & Products', 'aeris-wordpress-theme' ),
            'archives'              => __( 'Item Archives', 'aeris-wordpress-theme' ),
            'attributes'            => __( 'Item Attributes', 'aeris-wordpress-theme' ),
            'parent_item_colon'     => __( 'Parent Item:', 'aeris-wordpress-theme' ),
            'all_items'             => __( 'All Items', 'aeris-wordpress-theme' ),
            'add_new_item'          => __( 'Add New Item', 'aeris-wordpress-theme' ),
            'add_new'               => __( 'Add New', 'aeris-wordpress-theme' ),
            'new_item'              => __( 'New Item', 'aeris-wordpress-theme' ),
            'edit_item'             => __( 'Edit Item', 'aeris-wordpress-theme' ),
            'update_item'           => __( 'Update Item', 'aeris-wordpress-theme' ),
            'view_item'             => __( 'View Item', 'aeris-wordpress-theme' ),
            'view_items'            => __( 'View Items', 'aeris-wordpress-theme' ),
            'search_items'          => __( 'Search Item', 'aeris-wordpress-theme' ),
            'not_found'             => __( 'Not found', 'aeris-wordpress-theme' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'aeris-wordpress-theme' ),
            'featured_image'        => __( 'Featured Image', 'aeris-wordpress-theme' ),
            'set_featured_image'    => __( 'Set featured image', 'aeris-wordpress-theme' ),
            'remove_featured_image' => __( 'Remove featured image', 'aeris-wordpress-theme' ),
            'use_featured_image'    => __( 'Use as featured image', 'aeris-wordpress-theme' ),
            'insert_into_item'      => __( 'Insert into item', 'aeris-wordpress-theme' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'aeris-wordpress-theme' ),
            'items_list'            => __( 'Items list', 'aeris-wordpress-theme' ),
            'items_list_navigation' => __( 'Items list navigation', 'aeris-wordpress-theme' ),
            'filter_items_list'     => __( 'Filter items list', 'aeris-wordpress-theme' ),
        );
        $rewriteProducts = array(
            'slug'                  => 'product',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $argsProducts = array(
            'label'                 => __( 'Product', 'aeris-wordpress-theme' ),
            'description'           => __( 'Post Type Description', 'aeris-wordpress-theme' ),
            'labels'                => $labelsProducts,
            'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
            'taxonomies'            => array( 'category', 'theme', 'typeproduct', 'cestag' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 20,
            'menu_icon'             => 'dashicons-chart-area',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewriteProducts,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
        );
        register_post_type( 'products', $argsProducts );

        //
        // CUSTOM POST ART 
        //

        $labelsART = array(
            'name'                  => _x( 'ART', 'Post Type General Name', 'aeris-wordpress-theme' ),
            'singular_name'         => _x( 'ART', 'Post Type Singular Name', 'aeris-wordpress-theme' ),
            'menu_name'             => __( 'ART', 'aeris-wordpress-theme' ),
            'name_admin_bar'        => __( 'ART', 'aeris-wordpress-theme' ),
            'archives'              => __( 'Item Archives', 'aeris-wordpress-theme' ),
            'attributes'            => __( 'Item Attributes', 'aeris-wordpress-theme' ),
            'parent_item_colon'     => __( 'Parent Item:', 'aeris-wordpress-theme' ),
            'all_items'             => __( 'All Items', 'aeris-wordpress-theme' ),
            'add_new_item'          => __( 'Add New Item', 'aeris-wordpress-theme' ),
            'add_new'               => __( 'Add New', 'aeris-wordpress-theme' ),
            'new_item'              => __( 'New Item', 'aeris-wordpress-theme' ),
            'edit_item'             => __( 'Edit Item', 'aeris-wordpress-theme' ),
            'update_item'           => __( 'Update Item', 'aeris-wordpress-theme' ),
            'view_item'             => __( 'View Item', 'aeris-wordpress-theme' ),
            'view_items'            => __( 'View Items', 'aeris-wordpress-theme' ),
            'search_items'          => __( 'Search Item', 'aeris-wordpress-theme' ),
            'not_found'             => __( 'Not found', 'aeris-wordpress-theme' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'aeris-wordpress-theme' ),
            'featured_image'        => __( 'Featured Image', 'aeris-wordpress-theme' ),
            'set_featured_image'    => __( 'Set featured image', 'aeris-wordpress-theme' ),
            'remove_featured_image' => __( 'Remove featured image', 'aeris-wordpress-theme' ),
            'use_featured_image'    => __( 'Use as featured image', 'aeris-wordpress-theme' ),
            'insert_into_item'      => __( 'Insert into item', 'aeris-wordpress-theme' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'aeris-wordpress-theme' ),
            'items_list'            => __( 'Items list', 'aeris-wordpress-theme' ),
            'items_list_navigation' => __( 'Items list navigation', 'aeris-wordpress-theme' ),
            'filter_items_list'     => __( 'Filter items list', 'aeris-wordpress-theme' ),
        );
        $rewriteART = array(
            'slug'                  => 'artlist',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $argsART = array(
            'label'                 => __( 'ART', 'aeris-wordpress-theme' ),
            'description'           => __( 'Post Type Description', 'aeris-wordpress-theme' ),
            'labels'                => $labelsART,
            'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
            'taxonomies'            => array( 'category', 'theme', 'arttag' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 20,
            'menu_icon'             => 'dashicons-location-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewriteART,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
        );
        register_post_type( 'art', $argsART );

    }
    add_action( 'init', 'theia_wpthchild_custom_post', 0 );
    
}


/* ------------------------------------------------------------------------------------------------- */
/**
 * AJOUT DES FILTRES DES CUSTOM TAXONOMIES DANS LES LISTES DE POST / CUSTOM POST
 */

function theia_wpthchild_filter_by_custom_taxonomies( $post_type, $which ) {

	// Apply this only on a specific post type
	// if ( 'products' !== $post_type )
	// 	return;

    // A list of taxonomy slugs to filter by
    if ( 'products' == $post_type) {
	    $taxonomies = array( 'theme', 'cestag', 'typeproduct' );
    } else {
        $taxonomies = array( 'theme', 'cestag', 'arttag' );
    }

	foreach ( $taxonomies as $taxonomy_slug ) {

		// Retrieve taxonomy data
		$taxonomy_obj = get_taxonomy( $taxonomy_slug );
		$taxonomy_name = $taxonomy_obj->labels->name;

		// Retrieve taxonomy terms
		$terms = get_terms( $taxonomy_slug );

		// Display filter HTML
		echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
		echo '<option value="">' . sprintf( esc_html__( 'Show All %s', 'text_domain' ), $taxonomy_name ) . '</option>';
		foreach ( $terms as $term ) {
			printf(
				'<option value="%1$s" %2$s>%3$s</option>',
				$term->slug,
				( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
				$term->name
            );
            /* Avec le compteur, mais qui est faux car il prend en compte tous les types de post
             printf(
				'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
				$term->slug,
				( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
				$term->name,
				$term->count
            );
            */
		}
		echo '</select>';
    }
}
add_action( 'restrict_manage_posts', 'theia_wpthchild_filter_by_custom_taxonomies' , 10, 2);

// Renvoie le slug du term de la taxo "theme" modifié pour l'appel du bon id sur le SVG
function theia_wpthchild_svg_id($slug) {
    $svgID=array(
        "agriculture" => "agriculture",
        "agriculture-en" => "agriculture",
        "biodiversite" => "biodiversite",
        "biodiversity" => "biodiversite",
        "algorithmes" => "algorithmes",
        "algosprocessings" => "algorithmes",
        "eau" => "eau",
        "water" => "eau",
        "foret" => "foret",
        "forest" => "foret",
        "littoral" => "littoral",
        "coastline" => "littoral",
        "neigeglace" => "neigeglace",
        "snowice" => "neigeglace",
        "risques" => "risques",
        "naturalrisks" => "risques",
        "sante" => "sante",
        "health" => "sante",
        "urbain" => "urbain",
        "urban" => "urbain",
    );
    return $svgID[$slug];
}

/**
 * Filtre pour supprimer le préfixe du titre dans les archives "Catégorie: , Tag:, Custom Tax:, etc..."
 */

function theia_wpthchild_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }
  
    return $title;
}
 
add_filter( 'get_the_archive_title', 'theia_wpthchild_archive_title' );

/** CUSTOM PAGINATION  */
function theia_wpthchild_pagination_bar( $custom_query ) {

    $total_pages = $custom_query->max_num_pages;
    $big = 999999999; // need an unlikely integer

    if ($total_pages > 1){
        $current_page = max(1, get_query_var('paged'));

        echo paginate_links(array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => $current_page,
            'total' => $total_pages,
        ));
    }
}

require 'inc/theia-acf-config.php';
require 'inc/theia-acf-block.php';
require 'inc/theia-displays.php';

/**
 * Allow gutenberg reusable bloc on export screen
 */

function theia_wpthchild_support_reusable_block_export( $post_type_args, $post_type ) {
	if ( 'wp_block' === $post_type ) {
		$post_type_args['can_export'] = true;
	}
	return $post_type_args;
}
add_filter( 'register_post_type_args', 'theia_wpthchild_support_reusable_block_export', 10, 2 );