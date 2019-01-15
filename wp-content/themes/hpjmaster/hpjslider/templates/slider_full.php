<?php 
 $slides = new WP_Query( array( 'post_type' => 'home_slider', 'posts_per_page' => -1, 'order' => 'ASC', 'orderby' => 'menu_order' ) ); 
 if( $slides->have_posts() ) : $i = 0; $hackIE = array(); ?>
	<div id="imageWrap">
	   <div id="homeSlider" class="carousel slide" data-height="full">
			<?php /* <ol class="carousel-indicators">
			<li data-target="#homeSlider" data-slide-to="0" class="active"></li>
			<li data-target="#homeSlider" data-slide-to="1"></li>
			</ol> */ ?>
				<div class="carousel-inner">
				<?php while( $slides->have_posts() ) : $slides->the_post(); ?>
						<?php 
							$hackIE[get_the_ID()] = array();
							
							$custom = HPJ_get_post_custom();
							if(!empty($custom['wpcf-bg-img']) || !empty($custom['wpcf-bg-repeat']) || !empty($custom['wpcf-bg-position']) || !empty($custom['wpcf-bg-color'])){
								$bg = "";
								
								if(!empty($custom['wpcf-bg-img'])){
									$bg .= " url(".$custom['wpcf-bg-img'].")";    
								}
								
								if(!empty($custom['wpcf-bg-repeat'])){ 
									$bg .= " ".$custom['wpcf-bg-repeat'];    
								}
								
								if(!empty($custom['wpcf-bg-position'])){
									$bg .= " ".$custom['wpcf-bg-position'];   
								}
								
								if(!empty($custom['wpcf-bg-color'])){
									$bg .= " ".$custom['wpcf-bg-color'];   
								}
								else{
									$bg .= " transparent";
								}
								
								if(!empty($custom['wpcf-bg-size'])){
									$bgsize = "cover";
									
									// Pour IE < 9
									$bgIE  = "filter: progid:DXImageTransform.Microsoft.AlphaImageLoader("
											."src='".$custom['wpcf-bg-img']."',"
											."sizingMethod='scale');";
									$hackIE[get_the_ID()]['#item'.get_the_ID()] = $bgIE;
								}    
							}
							else{
								$bg = null;    
								$bgsize = null;    
							}
							
							if(!empty($custom['wpcf-video-src'])){
								$video_src = $custom['wpcf-video-src'];
								   
							}
							
							if(!empty($custom['wpcf-text-color'])){
								$color = $custom['wpcf-text-color'];
							}
							else{
								$color = '#FFF';        
							}
							
							if(!empty($custom['wpcf-slider-titre'])){
								$titre = $custom['wpcf-slider-titre'];
							}
							else{
								$titre = get_the_title();        
							}
							
							if(!empty($custom['wpcf-slider-sous-titre'])){
								$stitre = $custom['wpcf-slider-sous-titre'];
							}
							else{
								$stitre = '';        
							}
							if(!empty($custom['wpcf-largeur-bloc'])){
								$largeurbloc = $custom['wpcf-largeur-bloc'];
							}
							else{
								$largeurbloc = '9';        
							}
							
							if(!empty($custom['wpcf-txt-bg-color'])){
									$textbg = $custom['wpcf-txt-bg-color']; 
							   
							   if(!empty($custom['wpcf-txt-bg-opacity'])){
									$textbg .= "; background: rgba(".hex2rgb($custom['wpcf-txt-bg-color'], true).", 0.5)"; 
									
									// Pour IE < 9
									$textbgIE = str_replace("#", "", $custom['wpcf-txt-bg-color']);
									$textbgIE  = "filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#80$textbgIE,endColorstr=#80$textbgIE);";
									$textbgIE .= "background: transparent !important; zoom: 1;"; 
									
									$hackIE[get_the_ID()]['#item'.get_the_ID().' .bgcontent'] = $textbgIE; 
							   }    
							}
							else{
								$textbg = "transparent";
							}
							
							if(!empty($custom['wpcf-txt-position'])){
								switch($custom['wpcf-txt-position']){
									case 'center' : $textbgposition = null;
													$textposition = 'width: 50%; margin: auto; text-align: center;';
													break;
									case 'right'  : $textbgposition = "position: absolute; top: 0; right: 0; display: block; z-index: 0;";
													$textposition = 'padding: 30px 20px;';
													break;
									case 'left'   : 
									default :       $textbgposition = "position: absolute; top: 0; left: 0;  display: block; z-index: 0;";
													$textposition = 'padding: 30px 20px;';
													break;    
								}
							}  
						?>
						<article id="item<?php echo get_the_ID(); ?>" class="item<?php echo ($i == 0) ? ' active' : ''; ?>" style="<?php echo ((!empty($bg)) ? "background:$bg;" : "").((!empty($bgsize)) ? "background-size:$bgsize;" : ""); ?>">
							<?php if(!empty($custom['wpcf-video-src'])) { ?>
								<?php if(!is_array($custom['wpcf-video-src'])){ ?>
									<?php /* <video id="video<?php echo get_the_ID(); ?>" class="visible-lg-block" width="100%" height="100%" controls=false autoplay loop muted src="<?php echo $custom['wpcf-video-src'] ?>"<?php echo (!empty($custom['wpcf-video-cover'])) ? ' poster="'.$custom['wpcf-video-cover'].'"' : ''; ?>> */ ?>   
									<video id="video<?php echo get_the_ID(); ?>" class="visible-lg-block" width="100%" height="100%" controls=false autoplay loop muted src="<?php echo $custom['wpcf-video-src'] ?>">   
								<?php } else { ?>
									<?php /* <video id="video<?php echo get_the_ID(); ?>" class="visible-lg-block" width="100%" height="100%" controls=false autoplay loop muted<?php echo (!empty($custom['wpcf-video-cover'])) ? ' poster="'.$custom['wpcf-video-cover'].'"' : ''; ?>> */?>
									<video id="video<?php echo get_the_ID(); ?>" class="visible-lg-block" width="100%" height="100%" controls=false autoplay loop muted>
									<?php foreach($custom['wpcf-video-src'] as $src){ ?>
										<?php 
											if(strpos($src, '.mp4') > 0){$src_type = 'video/mp4';} 
											elseif(strpos($src, '.webm') > 0){$src_type = 'video/webm';}
											elseif(strpos($src, '.ogv') > 0){$src_type = 'video/ogg';}  
										?>
										<source src="<?php echo $src; ?>" type="<?php echo (!empty($src_type)) ? $src_type : 'video/mp4'; ?>" />       
									<?php } ?>
								<?php } ?>
								<?php if(!empty($custom['wpcf-video-cover'])) { ?>
									<?php /* <img src="<?php echo $custom['wpcf-video-cover']; ?>" alt="<?php echo get_the_title();?>" /> */ ?>
									<div class="video-cover" style="background: url(<?php echo $custom['wpcf-video-cover']; ?>) no-repeat center center transparent; background-size: cover;"></div>
								<?php } ?>
								</video>
								<?php if(!empty($custom['wpcf-video-cover'])) { ?>
									<div class="video-cover visible-xs-block visible-sm-block visible-md-block" style="background: url(<?php echo $custom['wpcf-video-cover']; ?>) no-repeat center center transparent; background-size: cover;"></div>
								<?php } ?>
								
								<?php if(!empty($custom['wpcf-video-filter-color'])) { ?>
									<?php if(!is_array($custom['wpcf-video-filter-color'])){ ?>
										<div class="filter-color" style="background: <?php echo $custom['wpcf-video-filter-color']; ?>; <?php echo (!empty($custom['wpcf-video-filter-color-opacity'])) ? 'opacity : '.((int) $custom['wpcf-video-filter-color-opacity']/100).'; filter: opacity(alpha='.(int) $custom['wpcf-video-filter-color-opacity'].')' : ''; ?>"></div>
									<?php } else { ?>
										<?php foreach($custom['wpcf-video-filter-color'] as $i => $filter_src){ ?>
											<div class="filter-color" style="background: <?php echo $filter_src; ?>; <?php echo (is_array($custom['wpcf-video-filter-color-opacity']) && !empty($custom['wpcf-video-filter-color-opacity'][$i])) ? 'opacity : '.((int) $custom['wpcf-video-filter-color-opacity']/100).'; filter: opacity(alpha='.(int) $custom['wpcf-video-filter-color-opacity'].')' : ''; ?>"></div>
										<?php } ?>
									<?php } ?>
								<?php } ?>
								
								<?php if(!empty($custom['wpcf-video-filter-img'])) { ?>
									<?php if(!is_array($custom['wpcf-video-filter-img'])){ ?>
										<div class="filter-img" style="background: url(<?php echo $custom['wpcf-video-filter-img']; ?>) no-repeat top right transparent; "></div>
									<?php } else { ?>
										<?php foreach($custom['wpcf-video-filter-img'] as $filter_src){ ?>
											<div class="filter-img" style="background: url(<?php echo $filter_src; ?>) no-repeat top right transparent; "></div>
										<?php } ?>
									<?php } ?>
								<?php } ?>
							<?php } ?>
							<div class="container">
								<div class="row">
									<div class="post-content" style="<?php echo ((!empty($textbg)) ? " background:$textbg;" : "").((!empty($color)) ? "color:$color;" : ""); ?>">  
										<div class="lead clearfix" style=" <?php echo ((!empty($color)) ? "color:$color;" : ""); ?>">
											<h2 class="entry-title clearfix"><?php echo ((!empty($titre)) ? "$titre" : get_the_title());?></h2>
											<?php echo ((!empty($stitre)) ? "$stitre" : "");?>
										</div>
									</div>
								</div>
							</div>
						</article>
				<?php $i++; endwhile; ?>
				</div>
				<!-- Carousel nav -->
				<a class="carousel-control left" href="#homeSlider" data-slide="prev"><?php _e('Précédent'); ?></a>
				<a class="carousel-control right" href="#homeSlider" data-slide="next"><?php _e('Suivant'); ?></a>
				
				<a class="next-subpage" href="#contentWrap"><?php _e(''); ?></a>
		</div>
	</div>
	<?php if(!empty($hackIE)) : ?>
		<!--[if lt IE 9]>
			<style>        
				<?php foreach($hackIE as $item) : foreach($item as $selector => $css) : if(!empty($css)) : ?>
				<?php echo $selector; ?>{
					<?php echo $css; ?>      
				}   
				<?php endif; endforeach; endforeach; ?>
			</style>
		<![endif]-->
	<?php endif; ?>
<?php else: ?>
<?php endif; unset($i); ?>
<?php wp_reset_postdata(); ?>