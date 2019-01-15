<?php $boxes = get_productboxes( get_the_ID() ); ?>

<?php if (!empty($boxes)) : ?>
<div class="container-boxes">
  <div class="container">
    <h2><?php echo get_post_meta(get_the_ID(), 'wpcf-boxes-title')[0] ?></h2>
    <div class="row">
      <?php foreach($boxes as $box) : ?>
        <div class="col-md-4 container-boxes-col text-center">
          <image src="<?php echo $box['image']; ?>" />
          <h3><?php echo $box['title']; ?></h3>
          <p><?php echo $box['text']; ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>
