<?php
/**
 * For shortcode functionality
 */
class Plumba_Shortcodes {

	function __construct() {

		//Add buttons to tinymce
		add_action( 'media_buttons_context', array( $this, 'media_buttons_context' ) );

		//add some content to the bottom of the page
		//This will be shown in the inline modal
		add_action( 'admin_footer', array( &$this, 'add_inline_popup_content' ) );

		//Add the shortcode function
		add_shortcode( 'plumba', array( &$this, 'shortcode_plumba' ) );

	}

	function shortcode_plumba( $attributes ) {
		$arguments    = shortcode_atts( array( 'id' => 0, 'style' => 'standard', 'bars' => 1, 'thankyou' => 1 ), $attributes );
		$presentation = new Plumba_Presentation();
		return $presentation->display( $arguments['id'], $arguments['style'], $arguments['bars'], $arguments['thankyou'] );
	}

	function media_buttons_context( $context ) {

		$post_id   = ! empty( $_GET['post'] ) ? (int) $_GET['post'] : 0;
		$post_type = get_post_type( $post_id );

		if ( $post_type == 'plumba_qa' ) return $context;

		$image_btn = WP_PLUGIN_URL . '/plumba/images/plumba.png';
		$out       = '<a href="#TB_inline?width=250&height=400&inlineId=popup_plumba" class="thickbox" title="' . __( 'Add Plumba Poll', 'plumba' ) . '"><img src="' . $image_btn . '" alt="' . __( 'Add Plumba Poll', 'plumba' ) . '" /></a>';
		return $context . $out;
	}


	function add_inline_popup_content() {
		?>
  <!--suppress ALL -->
  <div id="popup_plumba" style="display:none; height: 400px;">
      <h2>Plumba</h2>

      <p>
				<?php _e( 'Choose a plumba post to insert into post!', 'plumba' ); ?>
				<br/>
          <!--suppress HtmlFormInputWithoutLabel -->
          <select id="plumba_select">
						<?php
						$args  = array(
							'numberposts'      => 100,
							'orderby'          => 'post_date',
							'order'            => 'DESC',
							'post_type'        => 'plumba_qa',
							'post_status'      => 'publish',
							'suppress_filters' => true,
						);
						$posts = get_posts( $args );
		foreach ( $posts as $post ) {
			echo '<option value="' . $post->ID . '">' . $post->post_title . '</option>';
		}
						?>
          </select>
      </p>

      <p>
				<?php _e( 'Style', 'plumba' ); ?>:<br />
          <select name="plumbs_style" id="plumba_style">
						<?php Plumba_Comments::wp_option_styles(); ?>
          </select>
      </p>

      <p>
          <input id="plumba_no_bars" type="checkbox" value="no_bars" /> <?php _e( 'Disable bars?', 'plumba' ); ?>
      </p>

      <p>
          <input id="plumba_thankyou" type="checkbox" value="1" checked /> <?php _e( 'Show "thank you"-messages?', 'plumba' ); ?>
      </p>

      <p>
          <input type="button"
                 onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', 0, plumbaSetShortCode()); tb_remove();"
                 class="button-primary" value="<?php _e( 'Insert', 'plumba' ); ?>" />&nbsp;
          <input type="button" onclick="tb_remove();" class="button-secondary"
                 value="<?php _e( 'Cancel', 'plumba' ); ?>" />
      </p>
  </div>
	<?php
	}


}

?>