<?php while ( have_posts() ) : the_post(); ?>

  <?php apply_filters( 'product_subpage_after_page_header', '' ); ?>

  <div class="container-fluid container-features-subheader">
    <div class="row display-table">
      <div class="col-md-6 no-padding display-table-cell vertical-align-bottom"><?php the_post_thumbnail('', array('class'=>'img-responsive')); ?></div>
      <div class="col-md-6 no-padding display-table-cell vertical-align-middle">
        <div class="txt-content">
          <?php the_content( '', true ); ?>
        </div>
      </div>
    </div>
  </div>

  <?php apply_filters( 'product_subpage_after_subheader', '' ); ?>

  <div class="container-subfeatures">
    <div class="container">

      <div class="row subfeatures-header">
        <div class="col-md-6">
          <?php $featuressubfeaturesHeader = get_featureSubfeaturesheader(get_the_ID()); ?>
          <h2><?php echo $featuressubfeaturesHeader['title']; ?></h2>
          <?php echo $featuressubfeaturesHeader['subtitle']; ?>
        </div>
      </div>

      <?php apply_filters( 'product_subpage_after_subfeatureheader', '' ); ?>

      <?php
        $applications_qry = get_childs_page( get_the_ID() );
        if ( $applications_qry->have_posts() ) : while ( $applications_qry->have_posts() ) :
          $applications_qry->the_post();
          $sub_features = get_subfeatures(get_the_ID());
      ?>
      <div class="row alternate-order">
        <div class="col-md-6 col-content-left">
          <h3><?php the_title(); ?></h3>
          <?php the_content(); ?>
          <?php if (!empty($sub_features)) : ?>
            <p class="features-title">Features</p>
            <ul class="features-list">
              <?php foreach($sub_features as $sub_feature) : ?>
                <li><?php echo $sub_feature; ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
        <div class="col-md-6 col-content-right">
          <?php the_post_thumbnail( '', array( 'class' => 'img-responsive' ) ); ?>
        </div>
      </div>

      <?php endwhile; endif; wp_reset_postdata(); ?>

    </div>
  </div>

  <?php apply_filters( 'product_subpage_after_childpages', '' ); ?>

  <?php
  $post_id = get_the_ID();
  $essentials_post_id = apply_filters( 'config_data', 'pages_id.vortex.studio_essentials' );
  if($post_id == $essentials_post_id) {
    get_template_part('templates/productversion-cta');
  } else {
    get_template_part('templates/getedition-cta');
  }
  ?>

<?php endwhile; ?>
