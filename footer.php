</main>
      <?php if(get_theme_mod('telefone')||get_theme_mod('email')) : ?>
      <footer id="entre-em-contato" class="footer">
        <div class="container">
          <h2 class="section-title">
            Entre em contato 
          </h2>
          <ul class="grid -contato">
            <?php if(get_theme_mod('telefone')) : ?>
            <li>
              <p class="fas fa-phone">
                <span>
                  ligue agora
                  <strong><?php echo get_theme_mod('telefone'); ?></strong> 
                </span>
              </p>
            </li>
            <?php endif; ?>
            <?php if(get_theme_mod('email')) : ?>
            <li>
              <p class="fas fa-envelope">
                <span>
                  envie-nos um e-mail  
                  <a title="<?php echo get_theme_mod('email'); ?>" href="mailto:<?php echo get_theme_mod('email'); ?>"><strong>eventos@rivieracocktails.com.br</strong></a>
                </span>
              </p>
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </footer>
      <?php endif; ?>
    </div> 
    <?php wp_footer(); ?>
  </body>
</html>