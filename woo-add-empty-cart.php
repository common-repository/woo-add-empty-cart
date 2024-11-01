<?php
/**
Plugin Name: Woo Add Empty Cart Button
Plugin URI: http://jomarph.com/
Description: Add empty cart button in cart page for Woocommerce only. You can choose where to display the empty cart button in front-end.
Author: Jomar Lipon
Author URI: http://jomarph.com
Version: 2.0.0
**/
if(!class_exists('WC_Empty_Cart')) {
	
	class WC_Empty_Cart {
		
		public function __construct() 
		{
			if ( (get_option('before_empty_cart_button') == 'yes') || (get_option('before_empty_cart_button') == '1') ) {
				add_action('woocommerce_before_cart', array($this,'pt_wc_before_empty_cart_button'));
			} 
			if ( (get_option('after_empty_cart_button') == 'yes') || (get_option('after_empty_cart_button') == '1') ) {
				add_action('woocommerce_after_cart_contents', array($this,'pt_wc_after_empty_cart_button'));
			}
			
			add_action('woocommerce_after_mini_cart', array($this,'after_mini_cart'),10,1);
			
			add_action('init', array($this,'pt_wc_clear_cart_url'));
			
			add_filter( 'woocommerce_general_settings', array($this,'add_a_wc_setting') );	
		}
		
		public function after_mini_cart ($cart) {
				
				global $woocommerce;
				$cart_url = $woocommerce->cart->get_cart_url();
		?>
			
				<a class="button emptycart" href="<?php echo $cart_url;?>?empty=empty-cart"><?php _e('Empty Cart','pt-emptycart'); ?></a>
				
		<?php
		}
		
		public function pt_wc_after_empty_cart_button($cart) {
			/* $cart = calling the cart */
			
			global $woocommerce;
			
			$cart_url = $woocommerce->cart->get_cart_url();
			?>
						<tr>
							<td colspan="6" class="actions">
								<?php 
								
								if(empty($_GET)) {?>
								
									<a class="button emptycart" href="<?php echo $cart_url;?>?empty=empty-cart"><?php _e('Empty Cart','pt-emptycart'); ?></a>
								
								<?php } else {?>
								
									<a class="button emptycart" href="<?php echo $cart_url;?>&empty=empty-cart"><?php _e('Empty Cart','pt-emptycart'); ?></a>
								
								<?php } ?>
							
							</td>
						</tr>

		<?php }		
		
		
		public function pt_wc_before_empty_cart_button($cart) {
			/* $cart = calling the cart */
			
			global $woocommerce;
			
			$cart_url = $woocommerce->cart->get_cart_url();
			?>
						
						<?php 
						
						if(empty($_GET)) {?>
							<div class="">
								<a class="button emptycart" style="display:inline-block;float:right;margin-bottom:10px;" href="<?php echo $cart_url;?>?empty=empty-cart"><?php _e('Empty Cart','pt-emptycart'); ?></a>
							</div>
						<?php } else {?>
						
							<div class="">
								<a class="button emptycart" style="display:inline-block;float:right;margin-bottom:10px;" href="<?php echo $cart_url;?>?empty=empty-cart"><?php _e('Empty Cart','pt-emptycart'); ?></a>
							</div>
						
						<?php } ?>
						
						
		


		<?php }

	
		public function pt_wc_clear_cart_url() {
						
			global $woocommerce;
			if( isset($_REQUEST['empty']) ) {
				$woocommerce->cart->empty_cart();
			}
			
		}
		
		public function add_a_wc_setting($settings) {
			
					$updated_settings = array();
					
					foreach($settings as $section){
						
							if ( isset( $section['id'] ) && 'general_options' == $section['id'] && isset( $section['type'] ) && 'sectionend' == $section['type'] ) {
								$updated_settings[] =
										array(
											'name'		=> __( 'Empty Cart Button before cart', 'pt-emptycart'),
											'desc'		=> __( '<em>If check, the empty cart button will display before cart table</em>', 'pt-emptycart'),
											'id' 		=> 'before_empty_cart_button',
											'default'	=> 'yes',
											'type' 		=> 'checkbox'
										);
								$updated_settings[] =
										array(
											'name'		=> __( 'Empty Cart Button after cart', 'pt-emptycart'),
											'desc'		=> __( '<em>If check, the empty cart button will display after cart table</em>', 'pt-emptycart'),
											'id' 		=> 'after_empty_cart_button',
											'default'	=> 'yes',
											'type' 		=> 'checkbox'
										);
							}
						    $updated_settings[] = $section;
					}
						

					return $updated_settings;
		}
		
	}
	$wchook = new WC_Empty_Cart();
	
}


?>