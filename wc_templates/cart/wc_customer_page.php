<?php
/**
 * Customer Page template
 *
 * @package Welcart
 * @subpackage Welcart_Basic
 */

get_header();
?>

	<div id="primary" class="site-content">
		<div id="content" class="cart-page" role="main">

		<?php
		if ( have_posts() ) :
			usces_remove_filter();
			?>

			<article class="post" id="wc_<?php usces_page_name(); ?>">

				<h1 class="cart_page_title"><?php esc_html_e( 'Customer Information', 'usces' ); ?></h1>

				<div id="customer-info">

					<div class="cart_navi">
						<ul>
							<li><?php esc_html_e( '1.Cart', 'usces' ); ?></li>
							<li class="current"><?php esc_html_e( '2.Customer Info', 'usces' ); ?></li>
							<li><?php esc_html_e( '3.Deli. & Pay.', 'usces' ); ?></li>
							<li><?php esc_html_e( '4.Confirm', 'usces' ); ?></li>
						</ul>
					</div><!-- .cart_navi -->

					<div class="header_explanation">
						<?php do_action( 'usces_action_customer_page_header' ); ?>
					</div><!-- .header_explanation -->

					<div class="error_message"><?php usces_error_message(); ?></div>

				<?php if ( usces_is_membersystem_state() ) : ?>

					<?php if ( ! wel_have_ex_order() ) : ?>
					<h5><?php esc_html_e( 'The member please enter at here.', 'usces' ); ?></h5>
					<?php endif; ?>

					<form action="<?php usces_url( 'cart' ); ?>" method="post" name="customer_loginform" onKeyDown="if(event.keyCode == 13){return false;}">
						<table width="100%" cellpadding="0" cellspacing="0" class="customer_form">
							<tr>
								<th scope="row"><?php esc_html_e( 'e-mail adress', 'usces' ); ?></th>
								<td><input name="loginmail" id="loginmail" type="text" value="<?php echo esc_attr( $usces_entries['customer']['mailaddress1'] ); ?>" style="ime-mode: inactive" /></td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'password', 'usces' ); ?></th>
								<td><input name="loginpass" id="loginpass" type="password" autocomplete="new-password" value="" /></td>
							</tr>
						</table>
					<?php if ( wel_have_ex_order() ) : ?>
						<p id="nav"><a class="lostpassword" href="<?php usces_url( 'lostmemberpassword' ); ?>"><?php esc_html_e( 'Did you forget your password?', 'usces' ); ?></a></p>
						<p id="nav"><a class="newmember" href="<?php usces_url( 'newmember' ); ?>&dlseller_transition=newmember"><?php esc_html_e( 'New enrollment for membership.', 'usces' ); ?></a></p>
					<?php endif; ?>
						<div class="send"><input name="customerlogin" class="to_memberlogin_button" type="submit" value="<?php esc_html_e( ' Next ', 'usces' ); ?>" /></div>
						<?php do_action( 'usces_action_customer_page_member_inform' ); ?>
					</form>
				<?php endif; ?>

				<?php if ( ! wel_have_ex_order() ) : ?>

					<?php if ( usces_is_membersystem_state() ) : ?>
					<h5><?php esc_html_e( 'The nonmember please enter at here.', 'usces' ); ?></h5>
					<?php endif; ?>

					<form action="<?php echo esc_url_raw( USCES_CART_URL ); ?>" method="post" name="customer_form" onKeyDown="if(event.keyCode == 13){return false;}">
						<table cellpadding="0" cellspacing="0" class="customer_form">
							<?php uesces_addressform( 'customer', $usces_entries, 'echo' ); ?>
							<tr>
								<th scope="row">
									<em><?php esc_html_e( '*', 'usces' ); ?></em>
									<?php esc_html_e( 'e-mail adress', 'usces' ); ?>
								</th>
								<td colspan="2">
									<input name="customer[mailaddress1]" id="mailaddress1" type="text" value="<?php echo esc_attr( $usces_entries['customer']['mailaddress1'] ); ?>" style="ime-mode: inactive" autocomplete="off" />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<em><?php esc_html_e( '*', 'usces' ); ?></em>
									<?php esc_html_e( 'e-mail adress', 'usces' ); ?>(<?php esc_html_e( 'Re-input', 'usces' ); ?>)
								</th>
								<td colspan="2">
									<input name="customer[mailaddress2]" id="mailaddress2" type="text" value="<?php echo esc_attr( $usces_entries['customer']['mailaddress2'] ); ?>" style="ime-mode: inactive" autocomplete="off" />
								</td>
							</tr>
						<?php if ( usces_is_membersystem_state() ) : ?>
							<tr>
								<th scope="row">
								<?php if ( 'editmemberfromcart' === $member_regmode ) : ?>
									<em><?php esc_html_e( '*', 'usces' ); ?></em>
								<?php endif; ?><?php esc_html_e( 'password', 'usces' ); ?>
								</th>
								<td colspan="2">
									<input name="customer[password1]" type="password" value="<?php echo esc_attr( $usces_entries['customer']['password1'] ); ?>" autocomplete="new-password" />
								<?php if ( 'editmemberfromcart' !== $member_regmode ) : ?>
									<?php esc_html_e( 'When you enroll newly, please fill it out.', 'usces' ); ?>
								<?php endif; ?>
									<?php wel_password_policy_message(); ?>
								</td>
							</tr>
							<tr>
								<th scope="row">
								<?php if ( 'editmemberfromcart' === $member_regmode ) : ?>
									<em><?php esc_html_e( '*', 'usces' ); ?></em>
								<?php endif; ?>
									<?php esc_html_e( 'Password (confirm)', 'usces' ); ?>
								</th>
								<td colspan="2">
									<input name="customer[password2]" type="password" value="<?php echo esc_attr( $usces_entries['customer']['password2'] ); ?>" autocomplete="new-password" />
								<?php if ( 'editmemberfromcart' !== $member_regmode ) : ?>
									<?php esc_html_e( 'When you enroll newly, please fill it out.', 'usces' ); ?>
								<?php endif; ?>
								</td>
							</tr>
						<?php endif; ?>
						</table>
						<input name="member_regmode" type="hidden" value="<?php echo esc_attr( $member_regmode ); ?>" />
						<div class="send">
							<?php usces_get_customer_button(); ?>
						</div><!-- .send -->
						<?php usces_agree_member_field(); ?>
						<?php do_action( 'usces_action_customer_page_inform' ); ?>
					</form>

				<?php endif; ?>

					<div class="footer_explanation">
						<?php do_action( 'usces_action_customer_page_footer' ); ?>
					</div><!-- .footer_explanation -->

				</div><!-- #customer-info -->

			</article><!-- .post -->

		<?php else : ?>

			<p><?php esc_html_e( 'Sorry, no posts matched your criteria.', 'usces' ); ?></p>

		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_footer();
