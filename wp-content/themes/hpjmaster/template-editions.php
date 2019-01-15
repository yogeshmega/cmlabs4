<?php
/**
 * Template Name: Editions
 */
$acf_editions_tagline = get_field('editions_tagline', $post->ID);
$acf_editions_title = get_field('editions_title', $post->ID);
$acf_editions_desc = get_field('editions_short_desc', $post->ID);

while ( have_posts() ) : the_post();
    the_content();
endwhile;
// Query our Editions



$args = array(
    'posts_per_page'   => -1,
    'offset'           => 0,
    'orderby'          => 'date',
    'order'            => 'ASC',
	'lang'			   => pll_current_language(),
    'post_type'        => 'cmlabs_edition',
    'post_status'      => 'publish',
    'suppress_filters' => true
);
$posts = get_posts($args);

$editions = array();
$feature_categories = array();
foreach ($posts as $post) {
    $acf_editions_link = get_field('editions_link', $post->ID);
    $acf_editions_banner = get_field('editions_banner', $post->ID);
    $acf_editions_subtitle = get_field('editions_subtitle', $post->ID);

    while( have_rows('editions_link') ): the_row();
		$link_label = get_sub_field('link_label');
		$link_page = get_sub_field('link_page');
	endwhile;

    // Add the edition to the editions array
    $editions[] = array(
        'name' => $post->post_title,
        'subtitle' => $acf_editions_subtitle,
        'description' => $post->post_content,
        'link' => $link_page,
        'link_text' => $link_label,
        'banner' => $acf_editions_banner,
    );
    // Get features from Advanced Custom Fields
    $acf_feature_categories = get_field('feature_category', $post->ID);
    foreach ($acf_feature_categories as $acf_feature_category) {
        $category_name = trim($acf_feature_category['category_name']);
        $edition_features = isset($feature_categories[$category_name]['features']) ? $feature_categories[$category_name]['features'] : array();
        $feature_categories[$category_name] = array(
            'category' => $category_name,
            'features' => $edition_features,
        );

        foreach ($acf_feature_category['feature'] as $feature) {
            $feature_name = trim($feature['feature_name']);
            $edition_array = isset($feature_categories[$category_name]['features'][$feature_name]['editions']) ? $feature_categories[$category_name]['features'][$feature_name]['editions'] : array();
            $edition_array[] = $feature['feature_value'];
            $feature_categories[$category_name]['features'][$feature_name] = array(
                'description' => $feature_name,
                'editions' => $edition_array,
            );
        }
    }
}
?>
<div class="container">
   <table class="compare-headers">
    <tr>
      <td class="compare-header compare-header__intro">
            <h1 class="compare-header__title">
                <?php echo $acf_editions_title; ?>
            </h1>
            <div class="compare-header__description">
                <?php echo $acf_editions_desc; ?>
            </div>
      </td>
      <?php foreach ($editions as $edition) : ?>
        <td class="compare-header compare-header__<?php echo strtolower($edition['name']); ?> match-height">
          <?php if (!empty($edition['banner'])) : ?>
            <div class="compare-header__banner">
              <?php echo $edition['banner']; ?>
            </div>
          <?php endif; ?>
          <h2 class="compare-header__title"><?php echo $edition['name']; ?><span class="compare-header__subtitle"><?php echo $edition['subtitle']; ?></span></h2>
          <div class="compare-header__description">
            <?php echo $edition['description']; ?>
          </div>
          <?php if (!empty($edition['link'])) : ?>
            <a href="<?php echo $edition['link']; ?>" class="btn btn_edition btn_compare-header"><?php echo $edition['link_text']; ?></a>
            <?php endif; ?>
        </td>
      <?php endforeach; ?>
    </tr>
  </table>

  <div class="compare__wrap">
    <div class="compare__vertical-title"><?php echo $acf_editions_tagline; ?></div>
    <table class="compare-features">
      <?php foreach ($feature_categories as $feature_category) : ?>
        <tr class="compare-body__feature-category-row">
          <th class="compare-body__feature-category" colspan="5"><?php echo $feature_category['category']; ?></th>
        </tr>
        <?php foreach ($feature_category['features'] as $feature) : ?>
          <tr class="compare-body__feature">
            <th><?php echo $feature['description']; ?></th>
            <?php foreach ($feature['editions'] as $edition) : ?>
              <td>
                <?php
                switch ($edition) {
                  case 'Yes':
                    echo '<img src="'. get_template_directory_uri() .'/assets/images/check.png">';
                    break;
                  case 'No':
                    // Hidden because unused for now
                    // echo '<img src="'. get_template_directory_uri() .'/assets/images/close.png">';
                    break;
                  default:
                    echo $edition;
                    break;
                } ?>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      <?php endforeach; ?>
      <tr class="compare-body__ctas">
        <th><!-- empty --></th>
        <?php foreach ($editions as $edition) : ?>
          <td class="compare-body__cta"><a href="<?php echo $edition['link']; ?>" class="btn btn_edition btn_<?php echo strtolower($edition['name']); ?>"><?php echo $edition['link_text']; ?></a></td>
        <?php endforeach; ?>
      </tr>
    </table>
  </div>
  <div class="compare-features-mobile">
    <?php foreach ($editions as $index => $edition) : ?>
      <div class="compare-features-mobile__edition">
        <div class="compare-features-mobile__edition-title compare-features-mobile__edition-title_<?php echo strtolower($edition['name']); ?>">
          <?php echo $edition['name']; ?>
        </div>
        <?php foreach ($feature_categories as $feature_category) : ?>
          <div class="compare-features-mobile__category">
            <div class="compare-features-mobile__category-title">
              <?php echo $feature_category['category']; ?>
            </div>
            <div class="compare-features-mobile__features">
              <?php
                $feature_category_chunks = array_chunk($feature_category['features'], 2);
                foreach ($feature_category_chunks as $feature_category_row) : ?>
                <div class="compare-features-mobile__feature-wrap">
                  <div class="compare-features-mobile__feature-row">
                    <?php foreach ($feature_category_row as $feature) : ?>
                      <div class="compare-features-mobile__feature">
                        <?php
                        switch ($feature['editions'][$index]) {
                          case 'Yes':
                            echo '<table><tr><td><img src="'. get_template_directory_uri() .'/assets/images/check.png"></td><td>' . $feature['description'] . '</td></tr></table>';
                            break;
                          case 'No':
                            echo '<table><tr><td><img src="'. get_template_directory_uri() .'/assets/images/close.png"></td><td>' . $feature['description'] . '</td></tr></table>';
                            break;
                          default:
                            echo $feature['editions'][$index] . ' ' . $feature['description'];
                            break;
                        } ?>
                      </div>
                    <?php endforeach; ?>
                    <?php // Add a table cell for display issues
                      if (count($feature_category_row) < 2) {
                          echo '<div class="compare-features-mobile__feature compare-features-mobile__feature_empty"></div>';
                      } ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="compare-features-mobile__cta">
          <a href="<?php echo $editions[$index]['link']; ?>" class="btn btn_edition btn_<?php echo strtolower($editions[$index]['name']); ?>"><?php echo $editions[$index]['link_text']; ?></a>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</div>