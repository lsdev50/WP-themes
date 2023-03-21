<?php
/**
 * Image Banner Widget
 *
 * @package woondershop-pt
 */

if ( ! class_exists( 'PW_Image_Banner' ) ) {
	class PW_Image_Banner extends WP_Widget {
		private $widget_id_base, $widget_name, $widget_description, $widget_class;

		public function __construct() {
			$this->widget_id_base     = 'image_banner';
			$this->widget_class       = 'widget-image-banner';
			$this->widget_name        = esc_html__( 'Image Banner', 'woondershop-pt' );
			$this->widget_description = esc_html__( 'Linkable banner with image background and custom text.', 'woondershop-pt' );

			parent::__construct(
				'pw_' . $this->widget_id_base,
				sprintf( 'ProteusThemes: %s', $this->widget_name ),
				array(
					'description' => $this->widget_description,
					'classname'   => $this->widget_class,
				)
			);

			// Enqueue needed admin CSS and JS (for color picker).
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_widget_scripts' ) );
		}

		/**
		 * Enqueue needed admin CSS and JS (for color picker).
		 */
		public function enqueue_widget_scripts() {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args widget arguments.
		 * @param array $instance widget data.
		 */
		public function widget( $args, $instance ) {
			$title         = empty( $instance['title'] ) ? '' : $instance['title'];
			$text          = empty( $instance['text'] ) ? '' : $instance['text'];
			$text_color    = empty( $instance['text_color'] ) ? '#000000' : $instance['text_color'];
			$text_size     = empty( $instance['text_size'] ) ? 'normal' : $instance['text_size'];
			$text_width    = empty( $instance['text_width'] ) ? 'fullwidth' : $instance['text_width'];
			$text_position = empty( $instance['text_position'] ) ? 'middle-center' : $instance['text_position'];
			$image         = empty( $instance['image'] ) ? '' : $instance['image'];
			$custom_url    = empty( $instance['custom_url'] ) ? '#' : $instance['custom_url'];
			$cta_text      = empty( $instance['cta_text'] ) ? '' : $instance['cta_text'];
			$new_tab       = empty( $instance['new_tab'] ) ? '_self' : '_blank';

			// title and button size
			$title_size_class = 'h3';
			$button_size_class = '';

			switch ( $text_size ) {
				case 'big':
					$title_size_class = 'h2';
					break;

				case 'small':
					$title_size_class = 'h4';
					$button_size_class = 'btn-sm';
					break;
			}

			// appearance depending on the $text_color
			$cta_lightness_class = 'image-banner__cta--dark-bg';

			if ( $this->is_light_color( $text_color ) ) {
				$cta_lightness_class = 'image-banner__cta--light-bg';
			}

			echo $args['before_widget'];
			?>
				<?php if ( empty( $cta_text ) ) : ?>
					<a href="<?php echo esc_url( $custom_url ); ?>" target="<?php echo esc_attr( $new_tab ); ?>" class="image-banner">
				<?php else : ?>
					<div class="image-banner">
				<?php endif; ?>
					<img class="img-fluid  image-banner__image" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>">
					<div class="image-banner__content  image-banner__content--<?php echo sanitize_html_class( $text_size ); ?>  image-banner__content--<?php echo sanitize_html_class( $text_position ); ?>  image-banner__content--<?php echo sanitize_html_class( $text_width ); ?>">
						<?php if ( ! empty( $title ) ) : ?>
							<div class="<?php echo sanitize_html_class( $title_size_class ); ?>  image-banner__title" style="color: <?php echo esc_attr( $text_color ); ?>">
								<?php echo wp_kses_post( $title ); ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $text ) ) : ?>
							<p class="image-banner__text" style="color: <?php echo esc_attr( $text_color ); ?>">
								<?php echo wp_kses_post( $text ); ?>
							</p>
						<?php endif; ?>
						<?php if ( ! empty( $cta_text ) ) : ?>
							<a href="<?php echo esc_url( $custom_url ); ?>" target="<?php echo esc_attr( $new_tab ); ?>" class="btn  <?php printf( '%s  %s', sanitize_html_class( $button_size_class, '' ), sanitize_html_class( $cta_lightness_class, '' ) ); ?>  image-banner__cta"
							<?php if ( WoonderShopHelpers::is_skin_active( 'default' ) ) : ?>
								style=" background-color: <?php echo esc_attr( $text_color ); ?>; border-color: <?php echo esc_attr( $text_color ); ?>"
							<?php endif; ?>
							><?php echo esc_html( $cta_text ); ?></a>
						<?php endif; ?>
					</div>
				<?php if ( empty( $cta_text ) ) : ?>
					</a>
				<?php else : ?>
					</div>
				<?php endif; ?>
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

			$instance['title']         = wp_kses_post( $new_instance['title'] );
			$instance['text']          = wp_kses_post( $new_instance['text'] );
			$instance['text_color']    = sanitize_text_field( $new_instance['text_color'] );
			$instance['text_position'] = empty( $new_instance['text_position'] ) ? 'middle-center' : sanitize_key( $new_instance['text_position'] );
			$instance['text_size']     = sanitize_key( $new_instance['text_size'] );
			$instance['text_width']    = sanitize_key( $new_instance['text_width'] );
			$instance['image']         = esc_url_raw( $new_instance['image'] );
			$instance['custom_url']    = esc_url_raw( $new_instance['custom_url'] );
			$instance['cta_text']      = esc_html( $new_instance['cta_text'] );
			$instance['new_tab']       = empty( $new_instance['new_tab'] ) ? '' : sanitize_key( $new_instance['new_tab'] );

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options.
		 */
		public function form( $instance ) {
			$title         = empty( $instance['title'] ) ? '' : $instance['title'];
			$text          = empty( $instance['text'] ) ? '' : $instance['text'];
			$text_color    = empty( $instance['text_color'] ) ? '#000000' : $instance['text_color'];
			$text_position = empty( $instance['text_position'] ) ? 'middle-center' : $instance['text_position'];
			$text_size     = empty( $instance['text_size'] ) ? 'normal' : $instance['text_size'];
			$text_width    = empty( $instance['text_width'] ) ? 'fullwidth' : $instance['text_width'];
			$image         = empty( $instance['image'] ) ? '' : $instance['image'];
			$custom_url    = empty( $instance['custom_url'] ) ? '' : $instance['custom_url'];
			$cta_text      = empty( $instance['cta_text'] ) ? '' : $instance['cta_text'];
			$new_tab       = empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];

			$text_position_rows    = ['top', 'middle', 'bottom'];
			$text_position_columns = ['left', 'center', 'right'];
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'woondershop-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Subtitle:', 'woondershop-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>" style="vertical-align: top;"><?php esc_html_e( 'Text color: ', 'woondershop-pt' ); ?></label>
				<input class="pw-color-picker" id="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text_color' ) ); ?>" type="text" value="<?php echo esc_attr( $text_color ); ?>" data-default-color="#000000"/>
			</p>
			<div>
				<label><?php esc_html_e( 'Text position: ', 'woondershop-pt' ); ?></label>
				<table>
					<?php foreach ( $text_position_rows as $row ) : ?>
						<tr>
							<?php foreach ( $text_position_columns as $column ) : ?>
								<td><input type="radio" <?php checked( $text_position, sprintf( '%1$s-%2$s', $row, $column ) ); ?> id="<?php echo esc_attr( $this->get_field_id( sprintf( '%1$s-%2$s', $row, $column ) ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text_position' ) ); ?>" value="<?php printf( '%1$s-%2$s', $row, $column ); ?>" /></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text_size' ) ); ?>"><?php esc_html_e( 'Text size:', 'woondershop-pt' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'text_size' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'text_size' ) ); ?>">
					<option value="small" <?php selected( $text_size, 'small' ) ?>><?php esc_html_e( 'Small', 'woondershop-pt' ); ?></option>
					<option value="normal" <?php selected( $text_size, 'normal' ) ?>><?php esc_html_e( 'Normal', 'woondershop-pt' ); ?></option>
					<option value="big" <?php selected( $text_size, 'big' ) ?>><?php esc_html_e( 'Big', 'woondershop-pt' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text_width' ) ); ?>"><?php esc_html_e( 'Text container width:', 'woondershop-pt' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'text_width' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'text_width' ) ); ?>">
					<option value="fullwidth" <?php selected( $text_width, 'fullwidth' ) ?>><?php esc_html_e( 'Full width', 'woondershop-pt' ); ?></option>
					<option value="halfwidth" <?php selected( $text_width, 'halfwidth' ) ?>><?php esc_html_e( 'Half width', 'woondershop-pt' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_html_e( 'Picture URL:', 'woondershop-pt' ); ?></label>
				<input class="widefat" style="margin-bottom: 6px;" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" value="<?php echo esc_attr( $image ); ?>" />
				<input type="button" onclick="ProteusWidgetsUploader.imageUploader.openFileFrame('<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>');" class="button button-secondary" value="<?php esc_html_e( 'Upload Image', 'woondershop-pt' ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'custom_url' ) ); ?>"><?php esc_html_e( 'Link:', 'woondershop-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'custom_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'custom_url' ) ); ?>" type="text" value="<?php echo esc_attr( $custom_url ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cta_text' ) ); ?>"><?php esc_html_e( 'Button text:', 'woondershop-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cta_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cta_text' ) ); ?>" type="text" value="<?php echo esc_attr( $cta_text ); ?>" />
				<small><?php esc_html_e( 'If this field is empty, then the whole widget will be click-able, otherwise the button will appear.', 'woondershop-pt' ); ?></small>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e( 'Open link in new tab', 'woondershop-pt' ); ?></label>
			</p>

			<script type="text/javascript">
				(function( $ ) {
					$( document ).ready(function() {
						// Initialize color picker (for Page builder, widgets.php and customizer).
						$( '.so-content .pw-color-picker' ).wpColorPicker();
						$( '#widgets-right .pw-color-picker' ).wpColorPicker();
					});
				})( jQuery );
			</script>

			<?php
		}

		/**
		 * When passed the hex color string it tells if the color is light or not
		 *
		 * @param  string  $color_str hex color code
		 * @return boolean
		 */
		private function is_light_color( $color_str = '' ) {
			$color_obj = new \Mexitek\PHPColors\Color( $color_str );

			return $color_obj->isLight();
		}
	}
	register_widget( 'PW_Image_Banner' );
}
