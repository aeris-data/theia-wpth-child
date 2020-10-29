<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package aeris
 */

get_header(); 

get_template_part( 'template-parts/header-content', 'archive' );
// recup le slug du term courant
$term = get_queried_object();
$affichage_portfolio = get_field('sedoo_affichage_en_portfolio', $term);
// var_dump($term);
?>

	<div id="content-area" class="wrapper archives">
		<main id="main" class="site-main" role="main">
		<?php
			if($affichage_portfolio != true) { // if portfolio then display it, if not just do the normal script
				if (get_the_archive_description()) {
					the_archive_description( '<div class="archive-description">', '</div>' );
				}

				// get Sub categories for Actualités & news
				$args = array('parent' => $term->term_id);
				$categories = get_categories( $args );
				if ($categories) {
					?>
					<div class="tag">
					<?php
					foreach($categories as $category) { 
						echo '<a href="' . get_category_link( $category->term_id ) . '" title="'. $category->description . '" ' . '>' . $category->name.' ('. $category->count . ')</a>';
					}
					?>
					</div>
					<?php
				}
			} else {
				?>
				<script>
					ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
				</script>
				<style>
					.sedoo_port_action_btn li:hover {
						background-color: <?php echo $code_color; ?> !important;
					}

					.sedoo_port_action_btn li.active {
						background-color: <?php echo $code_color; ?> !important;
					}
				</style>
				<?php 
				archive_do_portfolio_display($term);
			}

			
		?>

            <?php
            /**
             * WP_Query pour lister tous les types de posts
             */
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

			// var_dump($paged);
            $args = array(
				// 'post_type' => array('post'),
				'post_type' 			=> 'any',
                'post_status'           => array( 'publish' ),
				'posts_per_page'        => 9,            // -1 pour liste sans limite
				'paged'					=> $paged,
                // 'post__not_in'          => array($postID),    //exclu le post courant
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'slug',
                        'terms'    => $term->slug,
                    ),
                ),
            );
            $the_query = new WP_Query( $args );

			// The Loop
            if ( $the_query->have_posts() ) { ?>

			<section role="listNews" class="posts">
				
			<?php
				while ( $the_query->have_posts() ) {
                    $the_query->the_post();
				?>
				<div class="post-container">
				<?php
					get_template_part( 'template-parts/content', get_post_format() );

					// If comments are open or we have at least one comment, load up the comment template.
					// if ( comments_open() || get_comments_number() ) :
					// 	comments_template();
					// endif;
					?>
				</div>
				<?php
				} // End of the loop.
				?>				
			</section>
			<?php 
                the_posts_navigation();
                /* Restore original Post Data */
				wp_reset_postdata();
				?>
			<?php
            } else {
				get_template_part( 'template-parts/content', 'none' );
			} 
			
            ?>
			
		</main><!-- #main -->
		<?php 
		// get_sidebar();
		?>
	</div><!-- #content-area -->
<?php
get_footer();
?>

