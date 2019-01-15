<?php
    defined( 'ABSPATH' ) or die( 'No direct access!' );  
    if (!empty($downloads)) {
    $i = 0;
    ?>
	<p><?php 
	
	if ( isset( $_GET['previous'] ) ) {
		_e( 'This page lets you download previous versions of Vortex Studio, vegetation and human libraries as well as samples and tools to help you get started.', HPJ_CMLABS_I18N_DOMAIN ); 
	} else {
		_e( 'This page lets you download the latest version of Vortex Studio, vegetation and human libraries as well as samples and tools to help you get started.', HPJ_CMLABS_I18N_DOMAIN ); 
	}
	?> 
	<?php _e( 'Before downloading Vortex Studio, please make sure your computer meets the', HPJ_CMLABS_I18N_DOMAIN ); ?><a target="_blank" href="/wp-content/uploads/Vortex-Studio-2018b-Minimum-Requirements-1.pdf"> <?php _e( 'Vortex Studio minimum system requirements', HPJ_CMLABS_I18N_DOMAIN ); ?></a>.</p>
	<p><?php _e( 'To learn more about Vortex Studio installation,', HPJ_CMLABS_I18N_DOMAIN ); ?> <a target="_blank" href="/vortexstudiodocumentation/Vortex_User_Documentation/Content/Licensing/index_installation.html"><?php _e( 'consult our documentation', HPJ_CMLABS_I18N_DOMAIN ); ?></a>.</p>
	<br />
	
    <div class="panel-group" id="downloads" role="tablist" aria-multiselectable="true">
        <?php foreach ($downloads as $key => $values) { ?>
        <div class="panel-heading" role="tab" id="heading<?php echo $i;?>">
            <h3 class="panel-title">
                <a role="button" <?php if($i != 0) { echo 'data-toggle="collapse"'; } ?> data-parent="#downloads" href="#collapse<?php echo $i;?>" aria-expanded="<?php if($i == 0) { echo "true"; } else { echo "false";} ?>" aria-controls="collapse<?php echo $i;?>">
                    <?php echo $key; ?>
                </a>
            </h3>
        </div>
        <div id="collapse<?php echo $i;?>" class="panel-collapse collapse <?php if($i == 0) { echo "in"; } ?>" role="tabpanel" aria-labelledby="heading<?php echo $i;?>">
            <table class="white-table download-list responsive-table">
                <tbody>
                    <?php foreach ($values as $v) { ?>
                        <tr>
                            <!--<td><?php echo htmlspecialchars($v->id); ?></td>-->
                            <td class="col-sm-3"><a href="<?php echo htmlspecialchars($v->link); ?>"><?php echo htmlspecialchars($v->name); ?></a></td>
                            <!--<td><?php echo htmlspecialchars($v->link); ?></td>-->
                            <td class="col-sm-1 size"><b><?php echo htmlspecialchars($v->size); ?></b></td>
                            <!--
                            <td class="col-sm-2 platform"><b><?php echo ucfirst(htmlspecialchars($v->platform)); ?></b></td>
                            <td><?php echo date('Y-m-d', strtotime($v->cdate)); ?></td>
                            -->
                            <td class="col-sm-8 requirement"><b><?php echo htmlspecialchars($v->requirement); ?></b></td>
                            <td class="info-block"><?php if (!empty($v->description)) { ?><a class="btn-info-tip" href="#" data-toggle="tooltip" data-placement="left" title="<?php echo htmlspecialchars($v->description); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-question.png" alt=""></a><?php } ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php $i++; }  ?>
    </div>
    <?php
    }
?>
<script>
    jQuery('.no-collapse').on('hide.bs.collapse', function (e) {
        preventDefault(e);
    });

</script>
<?php if ( isset( $_GET['previous'] ) ) { ?>
	<p><a class="pull-right" href="https://my.vxsim.com/" target="_blank"><?php _e('Looking for Vortex Software Solution 6.8 and earlier? Click here', HPJ_CMLABS_I18N_DOMAIN);?></a></p>
<?php } else { ?>
	<p><a class="pull-right" href="/downloads/?previous=1"><?php _e('Previous versions of Vortex Studio', HPJ_CMLABS_I18N_DOMAIN); ?></a></p>
<?php }