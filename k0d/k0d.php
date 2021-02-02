<?
/*
 * Plugin Name: Вывод записей k0d
 */


add_action('wp_enqueue_scripts','pagination_style');
add_action('wp_footer','pagination_script');

function pagination_style(){
    wp_enqueue_style("css1","https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css");
    // стили навигации
	  wp_enqueue_style("k0d_style",plugins_url( "/pagination/buzina-pagination.min.css",__FILE__ ),false);
}
function pagination_script(){
  wp_enqueue_script("jq1","https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js");
	wp_enqueue_script("jq2","https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js");
	wp_enqueue_script("jq3","https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js");
  // скрипт навигации
  wp_enqueue_script("k0d_jq2",plugins_url( "/pagination/buzina-pagination.min.js",__FILE__ ),false);
  wp_enqueue_script("k0d_jq3",plugins_url( "/pagination/pagination.js",__FILE__ ),false);
}





add_theme_support( 'post-thumbnails' );

add_action('init', 'my_custom_init');
function my_custom_init(){
	register_post_type('k0d', array(
		'labels'             => array(
			'name'               => 'Запись',
			'singular_name'      => 'Запись',
			'add_new'            => 'Добавить запись',
			'add_new_item'       => 'Добавить новую запись',
			'edit_item'          => 'Редактировать запись',
			'new_item'           => 'Новая запись',
			'view_item'          => 'Посмотреть запись',
			'search_items'       => 'Найти запись',
			'not_found'          =>  'Запись не найдена',
			'not_found_in_trash' => 'В корзине запись не найдена',
			'parent_item_colon'  => '',
			'menu_name'          => 'Записи k0d'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => 4,
		'supports'           => array('title','editor','thumbnail','excerpt','page-attributes')
	) );
}


add_shortcode( 'k0d', 'k0d_shortcode' );

function k0d_shortcode( $atts ){
  global $post;
  $atts = (object) shortcode_atts( array(
		'post'   => null,
		'pagen' => null,
    'showfirst'  => 'new',
    'title'  => 'Хардкоооод!',     
	), $atts );

  if($atts->post!=null and $atts->showfirst!=null){

    $order = 'ASC';
    if($atts->showfirst=='old'){
      $order = 'DESC';
    }


    $posts = get_posts( array(
      'numberposts' => -1,
      'orderby'     => 'date',
      'order'       => $order,
      'post_type'   => $atts->post,
      'suppress_filters' => true,
    ));

    $out = '<h1>'.$atts->title.'</h1>';
    $out  .= '<div id="postsList" pagen="'.$atts->pagen.'">';
    foreach( $posts as $post ){
      setup_postdata($post);
      // картинка поста
      $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' );
      $out  .= '  
      <div class="media">
        <img src="'.$thumbnail[0].'" class="mr-3">
        <div class="media-body">
          <div><small>'.get_the_date().'</small></div>
          <a class="mt-0 mb-1" href="'.get_permalink().'">'.get_the_title().'</a>
          <div>'.get_the_excerpt().'</div>
        </div>
      </div>
      ';
    }
    $out  .= '</div>';

    

    
    wp_reset_postdata();
    return  $out;
  }
}
?>