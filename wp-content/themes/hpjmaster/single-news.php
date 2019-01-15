<?php
use Roots\Sage\Setup;
use Roots\Sage\Wrapper;
?>
<div class="container">
  <div class="row">
    <main class="main main-container">
      <?php get_template_part('templates/content-news', get_post_type()); ?>
    </main><!-- /.main -->
    <?php if (Setup\display_sidebar()) : ?>
      <aside class="sidebar">
        <?php include Wrapper\sidebar_path(); ?>
      </aside><!-- /.sidebar -->
    <?php endif; ?>
  </div>
</div>