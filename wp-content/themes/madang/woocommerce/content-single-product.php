<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>
	
	<div class="summary entry-summary">
		<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>
		<?php
		 $metering = get_theme_mod( 'madang_metering', 'g' );
		 $metering_cal = get_theme_mod( 'madang_calories', 'kcal' );
		// specifying true as the last parameter in get_post_meta() returns the first value for the specified meta key ('madang_calories', 'madang_proteins' etc)
		$calories = get_post_meta( get_the_ID(), 'madang_calories', true);
		$proteins = get_post_meta( get_the_ID(), 'madang_proteins', true);
        $fats = get_post_meta( get_the_ID(), 'madang_fats', true);
        $carbohydrates = get_post_meta( get_the_ID(), 'madang_carbohydrates', true);
		?>
        <div class="nutrition-fact">
            <h6><?php echo esc_html__('Nutrition Facts', 'madang'); ?></h6>
				<div class="facts-table">
		            <table>
		                <tbody>
		                    <tr>
		                        <td><span><?php echo esc_html__('Calories', 'madang'); ?></span></td>
		                        <td><span class="cart_calories"><?php echo esc_attr(number_format($calories,0," "," ").$metering_cal); ?></span></td>
		                    </tr>
		                    <tr>
		                        <td><span class="cart_proteins"><?php echo esc_html__('Proteins', 'madang'); ?></span></td>
		                        <td><span><?php echo esc_attr(number_format($proteins,0," "," ").$metering); ?></span></td>
		                    </tr>
		                    <tr>
		                        <td><span><?php echo esc_html__('Fats', 'madang'); ?></span></td>
		                        <td><span class="cart_fats"><?php echo esc_attr(number_format($fats,0," "," ").$metering); ?></span></td>
		                    </tr>
		                    <tr>
		                        <td><span><?php echo esc_html__('Carbohydrates', 'madang'); ?></span></td>
		                        <td><span class="cart_carbohydrates"><?php echo esc_attr(number_format($carbohydrates,0," "," ").$metering); ?></span></td>
		                    </tr>
		                </tbody>
		            </table>
		        </div>
        </div>
	</div><!-- .summary -->
    
	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>
</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
