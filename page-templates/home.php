<?php /* Template Name: Home */ ?>

<?php 
  get_header(); 
  $servicos = new WP_Query( array( 'post_type' => 'servicos', 'posts_per_page' => 4, 'order' => 'ASC'));
  $galeria = new WP_Query(array( 'post_type'      => 'servicos', 'posts_per_page' => -1, 'order' => 'ASC'));   
?>

<?php if(get_field('imagem_de_fundo','option')||get_field('imagem_central','option')) : ?>
<section id="banner" class="banner" style="background-image:url(<?php echo get_field('imagem_de_fundo','option'); ?>)">
  <div class="container">
    <?php if(get_field('imagem_central','option')) : ?>
    <h2 class="banner-logo">
      <img src="<?php echo get_field('imagem_central','option'); ?>" alt="<?php echo get_bloginfo('title'); ?>">
    </h2>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if(get_field('quem_somos','option')) : ?>
<section id="quem-somos" class="quem-somos">
  <div class="container">
    <h2 class="section-title">Quem Somos</h2>
    <p><?php echo get_field('quem_somos','option'); ?></p>
  </div>
</section>
<?php endif; ?>

<?php if(get_field('servico_texto','option') && $servicos->have_posts()=="1") : ?>
<section id="o-que-fazemos" class="o-que-fazemos">
  <div class="container">
    <h2 class="section-title">
      O que fazemos
    </h2>
    <p><?php echo get_field('servico_texto','option'); ?></p>
    <ul class="grid -o-que-fazemos">
      <?php while ($servicos->have_posts()) : $servicos->the_post(); ?>
        <li>
          <div>
            <div class="grid-thumbnail" style="background-image: url(<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>)"></div>
            <h3 class="grid-title">
              <?php echo get_the_title(); ?>
            </h3>
            <p>
              <?php echo get_the_excerpt(); ?>
            </p>
            <a href="<?php echo get_the_permalink(); ?>" class="btn -btn-1" title="Saiba mais">Saiba mais</a>
          </div>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>
</section>
<?php endif; ?>

<?php    
  if ( $galeria->have_posts() ) : ?>
  <section id="galeria-de-fotos" class="galeria-de-fotos">
    <div class="container">
      <h2 class="section-title">
        Galeria de Fotos 
      </h2>
      <div class="galeria">
        <div class="owl-carousel owl-theme owl-gallery">
          <?php 
            while ( $galeria->have_posts() ) : $galeria->the_post();
              if( have_rows('galeria') ) :                     
                while ( have_rows('galeria') ) : the_row();
                  if(get_sub_field('galeria_principal') == 1){
                        echo '<div class="owl-gallery-pic" style="background-image: url('.get_sub_field('imagem_da_galeria').')"></div>';
                  }
                endwhile;    
              endif;
            endwhile;                                       
          ?>
        </div>
      </div>
    </div>
  </section>
<?php
    endif;
    wp_reset_postdata();
?>

<?php if(get_field('depoimentos','option')) : ?>
<section id="depoimentos" class="depoimentos">
  <div class="container">
    <h2 class="section-title">Depoimentos</h2>
    <ul class="grid -depoimentos">
      <?php 
        if( have_rows('depoimentos', 'option') ) : 
          while ( have_rows('depoimentos', 'option') ) : the_row();      
      ?>
      <li>
        <blockquote>
          <p>
            <span>
              <?php echo get_sub_field('depoimento'); ?>
            </span>
            <span>
              <small>
                <?php echo get_sub_field('depoente'); ?><br/>
                <?php echo get_sub_field('empresa_depoente'); ?>             
              </small>
            </span>
          </p>
        </blockquote>
      </li>
      <?php endwhile; endif; ?>
    </ul>
  </div>
</section>
<?php endif; ?>

<?php if(get_field('nossos_drinks','option')) : ?>
<section id="nossos-drinks" class="nossos-drinks">
  <div class="container">
    <h2 class="section-title">
      Nossos Drinks
    </h2>
    <p><?php echo get_field('nossos_drinks_text','option'); ?></p>
    <div class="galeria">
      <div class="owl-carousel owl-theme owl-gallery">
        <?php 
          if( have_rows('nossos_drinks', 'option') ) : 
            while ( have_rows('nossos_drinks', 'option') ) : the_row();      
        ?>
        <div>
          <div class="owl-gallery-pic" style="background-image: url(<?php echo get_sub_field('imagem_do_drink'); ?>)">
            <div>
              <p>
                <span>
                <?php echo get_sub_field('receita_do_drink'); ?>
                </span>
              </p>
            </div>
          </div>
        </div>
        <?php endwhile; endif; ?>     
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if(get_field('cliente','option')) : ?>
<section id="nossos-clientes" class="nossos-clientes">
  <div class="container">
    <h2 class="section-title">
      Nossos Clientes 
    </h2>
    <p><?php echo get_field('nossos_clientes_texto','option'); ?></p>
    <div class="carousel">
      <ul class="owl-carousel owl-theme owl-item-carousel">
        <?php 
          if( have_rows('cliente', 'option') ) : 
            while ( have_rows('cliente', 'option') ) : the_row();      
        ?>
        <li>
          <a href="<?php echo (get_sub_field('url_do_cliente')) ? get_sub_field('url_do_cliente') : 'javascript:void(0)' ?>"><img src="<?php echo get_sub_field('logo_do_cliente'); ?>"></a>
        </li>
        <?php endwhile; endif; ?>     
      </ul>
    </div>
  </div>
</section>
<?php endif; ?>
<?php get_footer(); ?>