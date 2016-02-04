<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Post Type class used to register post types
 *
 * @author 	Gijs Jorissen
 * @since 	0.1
 *
 */
class Cuztom_Post_Type
{
	var $name;
	var $title;
	var $plural;
	var $args;
	var $labels;
	var $add_features;
	var $remove_features;

	/**
	 * Construct a new Cuztom Post Type
	 *
	 * @param 	string|array 	$name
	 * @param 	array 			$args
	 * @param 	array 			$labels
	 *
	 * @author 	Gijs Jorissen
	 * @since 	0.1
	 *
	 */
	function __construct( $name, $args = array(), $labels = array() )
	{
		if( ! empty( $name ) )
		{

			// If $name is an array, the first element is the singular name, the second is the plural name
			if( is_array( $name ) )
			{
				$this->name			= Cuztom::uglify( $name[0] );
				$this->title		= Cuztom::beautify( $name[0] );
				$this->plural 		= Cuztom::beautify( $name[1] );
			}
			else
			{
				$this->name			= Cuztom::uglify( $name );
				$this->title		= Cuztom::beautify( $name );
				$this->plural 		= Cuztom::pluralize( Cuztom::beautify( $name ) );
			}

			$this->args 			= $args;
			$this->labels 			= $labels;
			$this->add_features		= $this->remove_features = array();

			// Add to array for uninstall
			global $nm_uninstall;
			$nm_uninstall['post_types'][] = $this->name;

			// Add action to register the post type, if the post type doesnt exist
			if( ! post_type_exists( $this->name ) )
			{
				add_action( 'init', array( &$this, 'register_post_type' ) );
			}
		}
	}

	/**
	 * Register the Post Type
	 *
	 * @author 	Gijs Jorissen
	 * @since 	0.1
	 *
	 */
	function register_post_type()
	{
		// Set labels
		$labels = array_merge(
			array(
				'name' 					=> sprintf( _x( '%s', 'post type general name', 'cuztom' ), $this->plural ),
				'singular_name' 		=> sprintf( _x( '%s', 'post type singular title', 'cuztom' ), $this->title ),
				'menu_name' 			=> sprintf( __( '%s', 'cuztom' ), $this->plural ),
				'all_items' 			=> sprintf( __( 'All %s', 'cuztom' ), $this->plural ),
				'add_new' 				=> sprintf( _x( 'Add New', '%s', 'cuztom' ), $this->title ),
				'add_new_item' 			=> sprintf( __( 'Add New %s', 'cuztom' ), $this->title ),
				'edit_item' 			=> sprintf( __( 'Edit %s', 'cuztom' ), $this->title ),
				'new_item' 				=> sprintf( __( 'New %s', 'cuztom' ), $this->title ),
				'view_item' 			=> sprintf( __( 'View %s', 'cuztom' ), $this->title ),
				'items_archive'			=> sprintf( __( '%s Archive', 'cuztom' ), $this->title ),
				'search_items' 			=> sprintf( __( 'Search %s', 'cuztom' ), $this->plural ),
				'not_found' 			=> sprintf( __( 'No %s found', 'cuztom' ), $this->plural ),
				'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'cuztom' ), $this->plural ),
				'parent_item_colon'		=> sprintf( __( '%s Parent', 'cuztom' ), $this->title ),
			),
			$this->labels
		);

		// Post type arguments
		$args = array_merge(
			array(
				'label' 				=> sprintf( __( '%s', 'cuztom' ), $this->plural ),
				'labels' 				=> $labels,
				'public' 				=> true,
				'supports' 				=> array( 'title', 'editor' ),
				'has_archive'           => sanitize_title( $this->plural )
			),
			$this->args
		);

		// Register the post type
		register_post_type( $this->name, $args );
	}

	/**
	 * Add a taxonomy to the Post Type
	 *
	 * @param 	string|array 	$name
	 * @param 	array 			$args
	 * @param 	array 			$labels
	 * @return  object 			Cuztom_Post_Type
	 *
	 * @author 	Gijs Jorissen
	 * @since 	0.1
	 *
	 */
	function add_taxonomy( $name, $args = array(), $labels = array() )
	{
		// Call Cuztom_Taxonomy with this post type name as second parameter
		$taxonomy = new Cuztom_Taxonomy( $name, $this->name, $args, $labels );

		// For method chaining
		return $this;
	}

	/**
	 * Add post meta box to the Post Type
	 *
	 * @param   integer 		$id
	 * @param 	string 			$title
	 * @param 	array 			$fields
	 * @param 	string 			$context
	 * @param 	string 			$priority
	 * @return  object 			Cuztom_Post_Type
	 *
	 * @author 	Gijs Jorissen
	 * @since 	0.1
	 *
	 */
	function add_meta_box( $id, $title, $fields = array(), $context = 'normal', $priority = 'default' )
	{
		// Call Cuztom_Meta_Box with this post type name as second parameter
		$meta_box = new Cuztom_Meta_Box( $id, $title, $this->name, $fields, $context, $priority );

		// For method chaining
		return $this;
	}

	/**
	 * Add Options submenu to a parent page
	 *
	 * @param   integer 		$id
	 * @param 	string 			$title
	 * @param 	string 			$slug
	 * @param 	array 			$data
	 * @return  object 			Cuztom_Post_Type
	 *
	 * @since 	0.1
	 *
	 */
	function add_options_page( $id, $title, $slug, $data = array())
	{
		$options_page = new Cuztom_Options( $id, $title, $slug, $data);

		// For method chaining
		return $this;
	}

	/**
	 * Add Dynamic Fields submenu to the post type
	 *
	 * @param   integer 		$id
	 * @param 	string 			$title
	 * @return  object 			Cuztom_Post_Type
	 *
	 * @since 	0.1
	 *
	 */
	function add_dynamic_fields($id, $title)
	{
		// Set up the post edit slug + fields for dynamic options
		$post_type 	= $this->name;
		$slug 		= 'edit.php?post_type='.$post_type;
		$data 		= array(
						'bundle',
						array(
							array(
								'name' => 'field_type',
								'label' => 'Field Type',
								'description' => 'The input type.',
								'type' => 'select',
								'options' => array(
									'text' => 'Text',
									'textarea' => 'Textarea',
									'file' => 'File Upload',
									'image' => 'Image Upload'
								)
							),
							array(
								'name' => 'field_name',
								'label' => 'Field Name',
								'description' => 'The name of the input.<br><strong class="red">If this is changed after creation, any saved data will be lost</strong>',
								'type' => 'text'
							),
							array(
								'name' => 'field_label',
								'label' => 'Field Label',
								'description' => 'The prefixed label of the field',
								'type' => 'text'
							)
						)
					);

		$options_page = new Cuztom_Options($id, $title, $slug, $data, $post_type);

		// For method chaining
		return $this;
	}

	/**
	 * Add action to register support of certain features for a post type.
	 *
	 * @param 	string|array 	$feature 			The feature being added, can be an array of feature strings or a single string
	 * @return 	object 			Cuztom_Post_Type
	 *
	 * @author 	Abhinav Sood
	 * @since 	1.4.3
	 *
	 */
	function add_post_type_support( $feature )
	{
		$this->add_features	= (array) $feature;

		add_action( 'init', array( &$this, '_add_post_type_support' ) );

		// For method chaining
		return $this;
	}

	/**
	 * Register support of certain features for a post type.
	 *
	 * @author 	Abhinav Sood
	 * @since 	1.4.3
	 *
	 */
	function _add_post_type_support()
	{
		add_post_type_support( $this->name, $this->add_features );
	}

	/**
	 * Add action to remove support of certain features for a post type.
	 *
	 * @param 	string|array 	$feature 			The feature being removed, can be an array of feature strings or a single string
	 * @return 	object 			Cuztom_Post_Type
	 *
	 * @author 	Abhinav Sood
	 * @since 	1.4.3
	 *
	 */
	function remove_post_type_support( $feature )
	{
		$this->remove_features	= (array) $feature;

		add_action( 'init', array( &$this, '_remove_post_type_support' ) );

		// For method chaining
		return $this;
	}

	/**
	 * Remove support of certain features for a post type.
	 *
	 * @author 	Abhinav Sood
	 * @since 	1.4.3
	 *
	 */
	function _remove_post_type_support()
	{
		foreach( $this->remove_features as $feature )
		{
			remove_post_type_support( $this->name, $feature );
		}
	}

	/**
	 * Check if post type supports a certain feature
	 *
	 * @param 	string  		$feature    		The feature to check support for
	 * @return  boolean
	 *
	 * @author 	Abhinav Sood
	 * @since 	1.5.3
	 *
	 */
	function post_type_supports( $feature )
	{
	    return post_type_supports( $this->name, $feature );
	}

	/**
	 * Grabs dynamic fields by key and add it to a metabox field array
	 *
	 * @param string $option_name 	Option name of the dynamic text field in the wp_options table
	 *
	 * @return array
	 */
	function build_dynamic_fields($option_name)
	{
		// Get the fields from the wp_options table
		$dynamic_fields 		= array();
 		$dynamic_option 		= get_option($option_name);
		$fields 				= $dynamic_option['_'.$option_name];
		$prefix					= 'nm_dyn_';

		if (!empty($fields) && is_array($fields)) {

			foreach ($fields as $key => $field) {

				if (!empty($field)) {

					// Sets the type, name label + description
					$type 	= Cuztom::uglify($field['_field_type']);
					$name 	= Cuztom::uglify($prefix.$field['_field_name']);
					$label 	= Cuztom::beautify($field['_field_name']);
					$desc 	= $field['_field_desc'];

					if (!empty($name)) {
						// Adds builds array with the set values from above
						$dynamic_fields[] = array(
							'name' => $name,
							'label' => $label,
							'description' => $desc,
							'type' => $type
						);
					}

				}

			}

		}

		return $dynamic_fields;

	}

}
