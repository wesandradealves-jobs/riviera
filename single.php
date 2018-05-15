<?php get_header(); ?>
<?php if ( have_posts () ) : while (have_posts()) : the_post();  ?>
<section class="banner -pg-interna" style="background-image:url(<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>)"></section>
<section class="about">
  <div class="container">
    <h2 class="section-title"><?php echo get_the_title(); ?></h2>
    <?php the_excerpt(); ?>
  </div>
</section>
<?php if( have_rows('galeria') ): ?>
<section class="service-gallery">
  <div class="container">
    <div class="galeria">
      <div class="owl-carousel owl-theme owl-gallery">
        <?php while ( have_rows('galeria') ) : the_row(); ?>
        <div>
          <div class="owl-gallery-pic" style="background-image: url(<?php the_sub_field('imagem_da_galeria') ?>)"></div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
<?php endwhile; endif; ?>
<?php get_footer(); ?>