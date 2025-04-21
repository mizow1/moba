<?php
/**
 * Content Template
 *
 * @package Welcart
 * @subpackage Welcart_Basic
 */

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<h1><?php the_title(); ?></h1>

	<div class="entry-content">
		<?php the_content( __( '(more...)' ) ); ?>
	</div><!-- .entry-content -->

</article>
