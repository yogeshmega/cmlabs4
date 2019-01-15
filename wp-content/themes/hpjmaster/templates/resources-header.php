<?php
  $img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); 
  $banner_bg = $img[0];
  //echo $banner_bg;
?>
<div class="page-header" style="background-image: url('https://www.cm-labs.com/wp-content/uploads/landing_resources.png')">
  <div class="container">
    <h1><strong>Resources</strong></h1>
	
    <?php if ( 'en' == pll_current_language() ) { ?>
    <p>We've created a wealth of resources for you to watch, read and share. If there's something you can't find, <a href="/contact-us">contact us</a>.</p>
	<?php } else { ?>
		<p>Nous avons cr&eacute;&eacute; une multitude de ressources que vous pourrez regarder, lire et partager. S'il y a quelque chose que vous ne trouvez pas, <a href="/fr/nous-joindre">contactez-nous</a>.<br /><br />(Nos ressources ne sont disponibles qu'en anglais)</p>
	<?php } ?>
	
  </div>
</div>