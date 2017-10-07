<?php
/*
Plugin Name: JUNN Steemit Latest Posts
Plugin URI: https://github.com/junn279/junn-steemit-latest-posts
Description: Bagic Latest Posts using Steem API.
Version: 1.0
Author: Junn
Author URI: http://junn.in
License: Feel free to copy, modify and distribute
*/

class junn_steemit_latest_posts extends WP_Widget {

	public function __construct() 
	{
		$widget_title = "Latest Steemit Posts";				//Appearance -> Widget name
	    $widget_options = array( 
	      'classname' => 'junn_steemit_latest_posts',					//class name, not visible?
	      'description' => 'Get Latest Posts from Steemit!',	//Appearance -> Widget Description
	    );
	    parent::__construct( 'junn_steemit_latest_posts', $widget_title, $widget_options );
	}

	public function widget( $args, $instance ) 					//Contents of View(실제 화면)
	{
		$search_option = $instance['search_option'];
		$keyword = $instance['keyword'];
		$nrow = $instance['nrow'];
		$title_add = "";
		if(!($nrow >= 1 && $nrow <= 20))
		{
			$nrow = 10;
		}
		if($search_option == "account")
		{
			$query_tag = "/@".$keyword;
			$title_add = " @".$keyword;
		}
		else
		{
			$query_tag = "/hot/".$keyword;
			$title_add = " TAG:".$keyword;
		}

		$title = apply_filters( 'widget_title', $instance[ 'title' ].$title_add );
		$blog_title = get_bloginfo( 'name' );
		$tagline = get_bloginfo( 'description' );
		echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title'];

		  
		//<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		//<script src="//cdn.steemjs.com/lib/latest/steem.min.js"></script>
		/*<p><strong>Search Type:</strong> <?php echo $search_option ?></p>
		<p><strong>Keyword:</strong> <?php echo $keyword ?></p>
		<p><strong>Count:</strong> <?php echo $nrow ?></p>*/
		?>
		<p><div id="jslp_latest_steem_view" style="width:100%;"><img style="width:50px;height:50px;" src="<?=plugins_url('img/giphy.gif',__FILE__)?>"/></div></p>
		<?php 
		$translation_array = array(
			'query_tag' => $query_tag,
			'no_profile_image' => plugins_url("img/no-profile.png",__FILE__),
			'nrow' => $nrow
		);

		wp_enqueue_script('jslp-steem-js',plugins_url('js/script.js',__FILE__));
		wp_localize_script('jslp-steem-js', 'args', $translation_array );
		?>
		 <?php echo $args['after_widget'];
	}

	public function form( $instance ) 							//Appearnce -> Widget -> Setting에서 보이는 부분
	{
	  $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
	  <p>
	    <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
	    <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
	  </p>
	  <p>
	  <?php 
	  	if(empty($instance['search_option']) || $instance['search_option'] == "account")
	  	{
	  		$account_selected = "selected";
	  		$tag_selected = "";
	  	} 
	  	else
	  	{
	  		$account_selected = "";
	  		$tag_selected = "selected";	
	  	}
	  ?>
	  <label for="<?php echo $this->get_field_id( 'search_option' ); ?>">Search:</label>
	  <select id="<?php echo $this->get_field_id( 'search_option' ); ?>" name="<?php echo $this->get_field_name( 'search_option' ); ?>"> />
	  <option value="account" <?=$account_selected?>>Account ID</option>
	  <option value="tag" <?=$tag_selected?>>Tag</option>
	  </select> 
	  </p>
	  <p>
	  <?php 
	  $keyword = ! empty( $instance['keyword'] ) ? $instance['keyword'] : ''; 
	  ?>
	  
	  <p>
	    <label for="<?php echo $this->get_field_id( 'keyword' ); ?>">Keyword:</label>
	    <input type="text" id="<?php echo $this->get_field_id( 'keyword' ); ?>" name="<?php echo $this->get_field_name( 'keyword' ); ?>" value="<?php echo esc_attr( $keyword ); ?>" />
	  </p>
	  <?php 
	  $nrow = ! empty( $instance['nrow'] ) ? $instance['nrow'] : 10; 
	  ?>
	  
	  <p>
	    <label for="<?php echo $this->get_field_id( 'nrow' ); ?>">Count(1-20):</label>
	    <input type="number" id="<?php echo $this->get_field_id( 'nrow' ); ?>" name="<?php echo $this->get_field_name( 'nrow' ); ?>" value="<?php echo esc_attr( $nrow ); ?>" />
	  </p>
	  <?php 
	}
	public function update( $new_instance, $old_instance ) 		//Appearnce -> Widget -> Setting -> Click Save Button
	{
  		$instance = $old_instance;
  		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
  		$instance[ 'search_option'] = strip_tags( $new_instance ['search_option']);
  		$instance[ 'keyword'] = strip_tags( $new_instance ['keyword']);
  		$num = strip_tags( $new_instance ['nrow']);
  		if($num > 20)$num = 20;
  		if($num < 0)$num = 1;
  		$instance[ 'nrow'] = $num;
  		return $instance;
	}
}

	
function jslp_load_steemjs() {
	wp_enqueue_script('include-steem-js', '//cdn.steemjs.com/lib/latest/steem.min.js');
	
}
add_action( 'wp_enqueue_scripts', 'jslp_load_steemjs' );

function jslp_register_my_widget() { 
  register_widget( 'junn_steemit_latest_posts' );	//Save as [Class Name]
}
add_action( 'widgets_init', 'jslp_register_my_widget' );	//Don`t change

?>