<?php
if ( ! defined( 'ABSPATH' ) ) exit; 


$current_post_id = get_the_ID();
$published = (get_post_status( $current_post_id ) == 'publish' );

if($published && !isset($_COOKIE['kdwc_hide_pagebuilder_notice'])): ?>
	<?php do_action('show_pagebuilders_noticebox'); ?>
<?php endif; ?>
<h4 style="margin-bottom:8px;font-weight:normal;"><?php _e('Paste this shortcode to display the trigger', 'kd-wc'); ?></h4>
<?php
if ($published):
$shortcode = sprintf( '[kdwc id="%1$d"]', $current_post_id); ?>
<span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value='<?php echo $shortcode; ?>' class="large-text code"></span>
<!--<p style="text-align: center; margin: 5px auto;">-- <?php _e('Or', 'kd-wc'); ?> --</p>
<h4 style="margin-top:0; margin-bottom:0;"><?php _e('PHP code to paste in your template', 'kd-wc'); ?></h4>-->
<p class="php-shortcode-toggle-link"><span class="kdwc-turnme"><i class="fa fa-angle-down" aria-hidden="true"></i></span><?php _e('PHP Code (for developers)', 'kd-wc'); ?></p>

<div class="php-shortcode-toggle-wrap">
	<?php $php_code = sprintf( '<?php kdwc(%1$d); ?>', $current_post_id); ?>
	<span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value='<?php echo $php_code; ?>' class="large-text code"></span>
</div>

<?php else: ?>
<span class="shortcode"><input type="text" readonly="readonly" value='<?php _e('Publish to get the shortcode', 'kd-wc');?>' class="large-text code"></span>
<?php endif; ?>