<?php
/**
 * The Categories widget replaces the default WordPress Categories widget. This version gives total
 * control over the output to the user by allowing the input of all the arguments typically seen
 * in the wp_list_categories() function.
 *
 */
class Total_Users_Lite_Widget extends WP_Widget {

	// Prefix for the widget.
	var $prefix;

	// Textdomain for the widget.
	var $textdomain;

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.6.0
	 */
	function __construct() {
	
		// Give your own prefix name eq. your-theme-name-
		$prefix = '';
		
		// Set up the widget options
		$widget_options = array(
			'classname' => 'total-users-lite',
			'description' => esc_html__( 'Advanced widget displaying total users.', $this->textdomain )
		);

		// Set up the widget control options
		$control_options = array(
			'width' => 460,
			'height' => 350,
			'id_base' => "{$this->prefix}total-users-lite"
		);

		// Create the widget
		$this->WP_Widget( "{$this->prefix}total-users-lite", esc_attr__( 'Total Users Lite', $this->textdomain ), $widget_options, $control_options );
		
		// Load the widget stylesheet for the widgets admin screen
		add_action( 'load-widgets.php', array(&$this, 'total_users_lite_widget_admin_script_style') );
		
		// Print the user costum style sheet
		if ( is_active_widget(false, false, $this->id_base) ) {
			wp_enqueue_style( 'total-users-lite', TOTAL_USERS_LITE_URL . 'css/total-users-lite.css' );
			wp_enqueue_script( 'jquery' );
			add_action( 'wp_head', array( &$this, 'print_script') );
		}
	}

	// Push the widget stylesheet widget.css into widget admin page
	function total_users_lite_widget_admin_script_style() {
		wp_enqueue_style( 'total-users-lite-dialog', TOTAL_USERS_LITE_URL . 'css/dialog.css' );
		wp_enqueue_script( 'jquery' );
	}
	
	function print_script() {
		$settings = $this->get_settings();
		foreach ($settings as $key => $setting){
			$widget_id = $this->id_base . '-' . $key;
			if( is_active_widget( false, $widget_id, $this->id_base ) ) {
				// Print the widget style adnd script
			}
		}
	}
	
	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.6.0
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Set up the arguments for wp_list_categories(). */
		$args = array(
			'title_icon'		=> $instance['title_icon'],
			'totalLabel'		=> $instance['totalLabel'],
			'hideempty'			=> !empty( $instance['hideempty'] ) ? true : false,
			'show'				=> $instance['show'],
			'roles'				=> $instance['roles'],
			'bgImage'			=> $instance['bgImage'],
			'totalColor'		=> $instance['totalColor'],
			'totalBgColor'		=> $instance['totalBgColor'],
			'borderColor'		=> $instance['borderColor'],
			'roleBgColor' 		=> $instance['roleBgColor'],
			'roleColor'			=> $instance['roleColor'],
			'toggle_active'		=> $instance['toggle_active'],
			'intro_text'		=> $instance['intro_text'],
			'outro_text'		=> $instance['outro_text'],
			'customstylescript' => $instance['customstylescript']
		);

		// Output the theme's widget wrapper
		echo $before_widget;
		
		// If a title was input by the user, display it.
		if ( !empty( $instance['title_icon'] ) )
			$titleIcon = '<img class="titleIcon" alt="" src="' . $instance['title_icon'] . '" />';
		else
			$titleIcon = '';		

		// If a title was input by the user, display it
		if ( !empty( $instance['title'] ) )
			echo $before_title . $titleIcon . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;

		// Print intro text if exist
		if ( !empty( $instance['intro_text'] ) )
			echo '<p class="'. $this->id . '-intro-text intro-text">' . $instance['intro_text'] . '</p>';
		
		echo '<div id="'. $this->id . '-wrapper">';
		echo total_users($instance);
		echo '</div>';
		
		// Print outro text if exist
		if ( !empty( $instance['outro_text'] ) )
			echo '<p class="'. $this->id . '-outro_text outro_text">' . $instance['outro_text'] . '</p>';

		// Close the theme's widget wrapper
		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.6.0
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Set the instance to the new instance. */
		$instance = $new_instance;

		$instance['title'] 				= strip_tags( $new_instance['title'] );
		$instance['title_icon']			= strip_tags( $new_instance['title_icon'] );
		$instance['totalLabel'] 		= $new_instance['totalLabel'];
		$instance['hideempty'] 			= ( isset( $new_instance['hideempty'] ) ? 1 : 0 );
		$instance['show'] 				= $new_instance['show'];
		$instance['roles'] 				= $new_instance['roles'];
		$instance['bgImage'] 			= $new_instance['bgImage'];
		$instance['totalColor'] 		= $new_instance['totalColor'];
		$instance['totalBgColor'] 		= $new_instance['totalBgColor'];
		$instance['borderColor'] 		= $new_instance['borderColor'];
		$instance['roleBgColor'] 		= $new_instance['roleBgColor'];
		$instance['roleColor'] 			= $new_instance['roleColor'];
		$instance['float'] 				= $new_instance['float'];
		$instance['toggle_active'] 		= $new_instance['toggle_active'];
		$instance['intro_text'] 		= $new_instance['intro_text'];
		$instance['outro_text'] 		= $new_instance['outro_text'];
		$instance['customstylescript']	= $new_instance['customstylescript'];
		
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6.0
	 */
	function form( $instance ) {
		global $wp_roles;
		$allroles = total_users_lite_get_roles(false);
		$roles = array();
		foreach ( $allroles as $key => $role ) {
			$show[$key] = true;
			$roles[] = $key;
		}
		
		// Set up the default form values
		$defaults = array(
			'title' 			=> __( 'Total Users Lite', $this->textdomain ),
			'title_icon'		=> '',
			'totalLabel' 		=> __( 'Total Users Lite', $this->textdomain ),
			'hideempty' 		=> false,
			'show' 				=> $show,
			'roles' 			=> $roles,
			'totalColor'		=> '#4d4d4d',
			'totalBgColor'		=> '#ffffff',
			'borderColor'		=> '#4d4d4d',
			'bgImage' 			=> '',
			'roleBgColor' 		=> '#4d4d4d',
			'roleColor' 		=> '#ffffff',
			'float' 			=> 'left',
			'toggle_active'		=> array( 0 => true, 1 => false, 2 => false ),
			'intro_text' 		=> '',
			'outro_text' 		=> '',
			'customstylescript'	=> ''
		);

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<div class="pluginName">Total Users Lite<span class="pluginVersion"><?php echo TOTAL_USERS_LITE_VERSION; ?></span></div>
		
		<script type="text/javascript">
			// Tabs function
			jQuery(document).ready(function($){
				// Tabs function
				$('ul.nav-tabs li').each(function(i) {
					$(this).bind("click", function(){
						var liIndex = $(this).index();
						var content = $(this).parent("ul").next().children("li").eq(liIndex);
						$(this).addClass('active').siblings("li").removeClass('active');
						$(content).show().addClass('active').siblings().hide().removeClass('active');
	
						$(this).parent("ul").find("input").val(0);
						$('input', this).val(1);
					});
				});
				
				// Widget background
				$("#tupro-<?php echo $this->id; ?>").closest(".widget-inside").addClass("tuproWidgetBg");
			});
		</script>

		<div id="tupro-<?php echo $this->id ; ?>" class="tuproControls tabbable tabs-left">
			<ul class="nav nav-tabs">
				<li class="<?php if ( $instance['toggle_active'][0] ) : ?>active<?php endif; ?>">General<input type="hidden" name="<?php echo $this->get_field_name( 'toggle_active' ); ?>[]" value="<?php echo esc_attr( $instance['toggle_active'][0] ); ?>" /></li>				
				<li class="<?php if ( $instance['toggle_active'][1] ) : ?>active<?php endif; ?>">Advanced<input type="hidden" name="<?php echo $this->get_field_name( 'toggle_active' ); ?>[]" value="<?php echo esc_attr( $instance['toggle_active'][1] ); ?>" /></li>
				<li class="<?php if ( $instance['toggle_active'][2] ) : ?>active<?php endif; ?>">Upgrade<input type="hidden" name="<?php echo $this->get_field_name( 'toggle_active' ); ?>[]" value="<?php echo esc_attr( $instance['toggle_active'][2] ); ?>" /></li>
			</ul>
			<ul class="tab-content">
				<li class="tab-pane <?php if ( $instance['toggle_active'][0] ) : ?>active<?php endif; ?>">
					<ul>
						<li>
							<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', $this->textdomain ); ?></label>
							<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
						</li>					
						<li>
							<label for="<?php echo $this->get_field_id( 'hideempty' ); ?>">
							<input class="checkbox" type="checkbox" <?php checked( $instance['hideempty'], true ); ?> id="<?php echo $this->get_field_id( 'hideempty' ); ?>" name="<?php echo $this->get_field_name( 'hideempty' ); ?>" /><?php _e( 'Hide Empty', $this->textdomain ); ?></label>
							<span class="controlDesc"><?php _e( 'Hide role for empty user', $this->textdomain ); ?></span>
						</li>					
						<li>
							<label><?php _e( 'Visibility', $this->textdomain ); ?></label> 
							<span class="controlDesc"><?php _e( 'The role visibility, check to hide.', $this->textdomain ); ?></span>
							<div id="<?php echo $this->id; ?>roleWrapper">
							
								<?php $names = $wp_roles->get_names(); //$wp_roles is already set above?>
								<?php foreach ( $instance['roles'] as $role ) { ?>
									<?php $name = isset($names[$role]) ? $names[$role] : esc_attr( $instance['totalLabel'] ) ; ?>
									<div class="role">
										<label><input class="checkbox" type="checkbox" <?php checked( isset($instance['show'][$role]), true ); ?> name="<?php echo $this->get_field_name( 'show' ); ?>[<?php echo $role; ?>]" /><?php echo $name . '<span class="totalUser">' . $allroles[$role] . '</span>'; ?></label>
										<input type="hidden" name="<?php echo $this->get_field_name( 'roles' ); ?>[]" value="<?php echo $role; ?>">
									</div>
								<?php } ?>
								
							</div>
						</li>
					</ul>
				</li>
				<li class="tab-pane <?php if ( $instance['toggle_active'][1] ) : ?>active<?php endif; ?>">
					<ul>
						<li>
							<label for="<?php echo $this->get_field_id('intro_text'); ?>"><?php _e( 'Intro Text', $this->textdomain ); ?></label>
							<span class="controlDesc"><?php _e( 'This option will display addtional text before the widget content and HTML supports.', $this->textdomain ); ?></span>
							<textarea name="<?php echo $this->get_field_name( 'intro_text' ); ?>" id="<?php echo $this->get_field_id( 'intro_text' ); ?>" rows="2" class="widefat"><?php echo esc_textarea($instance['intro_text']); ?></textarea>
							
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('outro_text'); ?>"><?php _e( 'Outro Text', $this->textdomain ); ?></label>
							<span class="controlDesc"><?php _e( 'This option will display addtional text after widget and HTML supports.', $this->textdomain ); ?></span>
							<textarea name="<?php echo $this->get_field_name( 'outro_text' ); ?>" id="<?php echo $this->get_field_id( 'outro_text' ); ?>" rows="2" class="widefat"><?php echo esc_textarea($instance['outro_text']); ?></textarea>
							
						</li>				
					</ul>
				</li>
				<li class="tab-pane <?php if ( $instance['toggle_active'][2] ) : ?>active<?php endif; ?>">
					<ul>
						<li>
							<p><a target="_blank" href="http://codecanyon.net/item/total-users-lite-pro-wordpress-users-counter/3178157?ref=zourbuth"><span class="upgradePro"></span></a></a>Upgrade to <a target="_blank" href="http://codecanyon.net/item/total-users-lite-pro-wordpress-users-counter/3178157?ref=zourbuth">Total Users Lite Pro</a> for more plugin options and customizations.<p>						
							<p><label>Shortcode Editor</label><span class="controlDesc">You can use this plugin with shortcode in the post or page</span></p>							
							<p><label>General Total</label><span class="controlDesc">Additional section for general users total</span></p>							
							<p><label>Total Color</label><span class="controlDesc">The counter background color.</span></p>							
							<p><label>Total Background Color</label><span class="controlDesc">The counter background color.</span></p>							
							<p><label>Total Background Color</label><span class="controlDesc">The role background color.</span></p>							
							<p><label>Role Color</label><span class="controlDesc">The counter background color.</span></p>							
							<p><label>Border Color</label><span class="controlDesc">The counter background color.</span></p>							
							<p><label>Background Image</label><span class="controlDesc">The counter background image.</span></p>							
							<p><label>Float</label><span class="controlDesc">Floating style for each roles</span></p>							
							<p><label>Sort Order</label><span class="controlDesc">Becomes very easy to sort the roles with jQuery UI sortable</span></p>							
							<p><label>Custom Style & Script</label><span class="controlDesc">Easy to add your custom style and script for each selector.</span></p>													
							<p><label>Plugin Updates</label><span class="controlDesc">Notification for every available update.</span></p>
							<p><label>And Many More</label><span class="controlDesc">Full supports, documentation and more...</span></p>
							<p><h3><a target="_blank" href="http://codecanyon.net/item/total-users-lite-pro-wordpress-users-counter/3178157?ref=zourbuth">Upgrade Now!</a></h3></p>
							
							<input type="hidden" name="<?php echo $this->get_field_name( 'float' ); ?>" value="<?php echo esc_attr( $instance['float'] ); ?>">
						</li>
					</ul>
				</li>
			</ul>
		</div>
	<?php
	}
}

?>