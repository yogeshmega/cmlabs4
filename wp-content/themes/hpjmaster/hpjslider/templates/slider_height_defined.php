<?php  
 $slides = new WP_Query( array( 'post_type' => 'home_slider', 'posts_per_page' => -1, 'order' => 'ASC', 'orderby' => 'menu_order' ) ); 
 if( $slides->have_posts() ) : $i = 0; $hackIE = array(); ?>
	<div id="imageWrap">
	   <div id="homeSlider" class="carousel slide" data-height="608">
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
							
							if(!empty($custom['wpcf-text-color'])){
								$color = $custom['wpcf-text-color'];
							}
							else{
								$color = '#FFF';        
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
								$largeurbloc = '7';        
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
						<article id="item<?php echo get_the_ID(); ?>" class="parallax item<?php echo ($i == 0) ? ' active' : ''; ?>" style="<?php echo ((!empty($bg)) ? "background:$bg;" : "").((!empty($bgsize)) ? "background-size:$bgsize;" : ""); ?>">
						   
							<div class="container">
								<div class="row">
									<div class="post-content col-sm-<?=$largeurbloc;?>" style=" <?php echo ((!empty($textbg)) ? " background:$textbg;" : "").((!empty($color)) ? "color:$color;" : ""); ?>">  
										<div class="lead " style=" <?php echo ((!empty($color)) ? "color:$color;" : ""); ?>">
											<h2 class="entry-title"><?php echo the_title(); ?></h2>
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
		</div>
	</div>
	<?php if(!empty($hackIE)) : ?>
		<!--[if lte IE 8]>
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