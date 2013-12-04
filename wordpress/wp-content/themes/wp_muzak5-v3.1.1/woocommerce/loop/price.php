<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="product-price">
		<span class="price"><?php echo $price_html; ?></span>
	</span>
<?php endif; ?>