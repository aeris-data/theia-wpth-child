<?php 
    if ( get_post_type() !== 'post') { $classParameter="isNotPost";} else { $classParameter="post";}
?>
<div class="sedoo_portfolio_item">
    <article id="post-<?php the_ID(); ?>" <?php post_class('post '.$classParameter.''); ?>>
        <a href="<?php the_permalink(); ?>" title="<?php echo __('Read more', 'sedoo-wpth-labs'); ?>">
            <header class="entry-header">
                <figure>
                    <?php 
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('thumbnail-loop');
                    } else {
                        labs_by_sedoo_catch_that_image();                
                    }?>            
                </figure>
                <?php     
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                ?> 
                <p>
                    <?php 
                    echo esc_html( $categories[0]->name );   
                    ?>
                </p>
                <?php
                }
                ?>
            </header><!-- .entry-header -->
            <div class="group-content">
                <div class="entry-content">
                    <h3><?php the_title(); ?></h3>
                    <?php
                    if ( 'post' === get_post_type() ) :
                    ?>
                    <?php the_excerpt(); ?>
                    <?php endif; ?>
                </div><!-- .entry-content -->
                <?php
                if ( 'post' === get_post_type() ) :
                ?>
                <footer class="entry-footer">
                    <p><?php the_date('d.m.Y') ?></p>
                    <a href="<?php the_permalink(); ?>"><?php echo __('Read more', 'sedoo-wpth-labs'); ?> →</a>
                </footer><!-- .entry-footer -->
                <?php endif; ?>
                </footer><!-- .entry-footer -->
            </div>
        </a>
    </article><!-- #post-->
</div>