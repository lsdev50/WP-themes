<?php
/**
 * Call to Action Widget
 *
 * @package woondershop-pt
 */

if ( ! class_exists( 'PW_Call_To_Action' ) ) {
	class PW_Call_To_Action extends WP_Widget   {

		/**
		 * PW_Call_To_Action constructor.
		 */
		public function __construct() {
			parent::__construct(
				'pw_call_to_action',
				sprintf( 'ProteusThemes: %s', esc_html__( 'Call to Action', 'woondershop-pt' ) ),
				array(
					'description' => esc_html__( 'Call to Action widget for Page Builder.', 'woondershop-pt' ),
					'classname'   => 'widget-call-to-action',
				)
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			?>
				<div class="call-to-action">
					<div class="call-to-action__text">
						<h2 class="call-to-action__title">
							<?php echo wp_kses_post( $instance['title'] ); ?>
						</h2>
						<?php if ( ! empty( $instance['subtitle'] ) ) : ?>
						<p class="call-to-action__subtitle">
							<?php echo wp_kses_post( $instance['subtitle'] ); ?>
						</p>
						<?php endif; ?>
					</div>
					<div class="call-to-action__button">
						<?php echo do_shortcode( $instance['button_text'] ); ?>
					</div>
				</div>
			<?php
			echo $args['after_widget'];
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @param array $new_instance The new options.
		 * @param array $old_instance The previous options.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['title']       = wp_kses_post( $new_instance['title'] );
			$instance['subtitle']    = wp_kses_post( $new_instance['subtitle'] );
			$instance['button_text'] = wp_kses_post( $new_instance['button_text'] );

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options.
		 */
		public function form( $instance ) {
			$title       = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$subtitle    = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : '';
			$button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : '';
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'woondershop-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><?php esc_html_e( 'Subtitle:', 'woondershop-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subtitle' ) ); ?>" type="text" value="<?php echo esc_attr( $subtitle ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Area:', 'woondershop-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" /><br><br>
				<span class="button-shortcodes">
					<?php printf( esc_html__( 'Input a button shortcode in the above field. Please take a look at the %1$sButtons Shortcode documentation%2$s to learn more on how to write button shortcodes.', 'woondershop-pt' ), '<a href="https://www.proteusthemes.com/docs/woondershop-pt/#buttons" target="_blank">', '</a>' ); ?><br>
				</span>
			</p>

			<?php
		}
	}
	register_widget( 'PW_Call_To_Action' );
}
