<?php

if ( ! class_exists('Clipchamp') ) {

	/**
	 * Main / front controller class
	 *
	 */
	class Clipchamp extends CCB_Module {
		protected static $readable_properties  = array();    // These should really be constants, but PHP doesn't allow class constants to be arrays
		protected static $writeable_properties = array();
		protected $modules;

		const VERSION    = '1.5.5';
		const PREFIX     = 'ccb_';
		const DEBUG_MODE = false;


		/*
		 * Magic methods
		 */

		/**
		 * Constructor
		 *
		 * @mvc Controller
		 */
		protected function __construct() {
			$this->register_hook_callbacks();

			$this->modules = array(
				'CCB_Welcome'			=> CCB_Welcome::get_instance(),
				'CCB_Settings'		    => CCB_Settings::get_instance(),
				'CCB_Video_Post_Type'   => CCB_Video_Post_Type::get_instance(),
				'CCB_Shortcode'		    => CCB_Shortcode::get_instance()
			);
		}


		/*
		 * Static methods
		 */

		/**
		 * Enqueues CSS, JavaScript, etc
		 *
		 * @mvc Controller
		 */
		public static function load_resources() {
            wp_register_script(
                self::PREFIX . 'admin-script',
                plugins_url( 'javascript/admin-script.js', dirname( __FILE__ ) ),
                array( self::PREFIX . 'admin-codemirror-script', self::PREFIX . 'admin-codemirror-js-script', 'wp-color-picker', 'jquery' ),
                self::VERSION,
                true
            );

            wp_register_script(
                self::PREFIX . 'admin-codemirror-script',
                '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.24.0/codemirror.min.js',
                array(),
                self::VERSION,
                true
            );

            wp_register_script(
                self::PREFIX . 'admin-codemirror-js-script',
                '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.24.0/mode/javascript/javascript.min.js',
                array( self::PREFIX . 'admin-codemirror-script' ),
                self::VERSION,
                true
            );

			wp_register_style(
				self::PREFIX . 'admin',
				plugins_url( 'css/admin.css', dirname( __FILE__ ) ),
				array(),
				self::VERSION,
				'all'
			);


			wp_register_style(
			    self::PREFIX . 'admin-codemirror',
                '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.24.0/codemirror.min.css',
                array(),
                self::VERSION,
                'all'
            );

			if ( is_admin() ) {
                wp_enqueue_media();
				wp_enqueue_style( self::PREFIX . 'admin' );
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_style( self::PREFIX . 'admin-codemirror' );
                wp_enqueue_script( self::PREFIX . 'admin-script' );
                wp_enqueue_script( self::PREFIX . 'admin-codemirror-script' );
                wp_enqueue_script( self::PREFIX . 'admin-codemirror-js-script' );
			}
		}

		/**
		 * Clears caches of content generated by caching plugins like WP Super Cache
		 *
		 * @mvc Model
		 */
		protected static function clear_caching_plugins() {
			// WP Super Cache
			if ( function_exists( 'wp_cache_clear_cache' ) ) {
				wp_cache_clear_cache();
			}

			// W3 Total Cache
			if ( class_exists( 'W3_Plugin_TotalCacheAdmin' ) ) {
				$w3_total_cache = w3_instance( 'W3_Plugin_TotalCacheAdmin' );

				if ( method_exists( $w3_total_cache, 'flush_all' ) ) {
					$w3_total_cache->flush_all();
				}
			}
		}

		/**
		 * Handles the file upload.
		 *
		 * @mvc Controller
		 */
		public static function upload() {
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}

			$upload_overrides = array( 'test_form' => false );

			if ( ! empty( $_FILES['video']['name'] ) ) {
				//TODO:Change upload dir
				$video = wp_handle_upload( $_FILES['video'], $upload_overrides );
			}

			if ( $video && ! isset( $video['error'] ) ) {

                $video = apply_filters('ccb_save_video', $video );

				$attachment = array(
					'guid'           => $video['url'],
					'post_mime_type' => $video['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $video['file'] ) ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				wp_insert_attachment( $attachment, $video['file'], 0 );

				wp_die( $video['url'] );

			} else {
                status_header(400);
				wp_die( $video['error'] );
			}
		}

        /**
         * Handles the file upload.
         *
         * @mvc Controller
         */
        public static function upload_image() {
            $post_id = esc_attr( $_POST['post_id'] );

            if ( isset( $_POST['post_id'], $_FILES['image'] ) && current_user_can( 'edit_post', $post_id ) ) {
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/media.php' );

                $thumbnail_id = media_handle_upload( 'image', $post_id );

                if ( ! is_wp_error( $thumbnail_id ) ) {
                    set_post_thumbnail( $post_id, $thumbnail_id );
                    echo $thumbnail_id;
                } else {
                    status_header(500);
                }
            } else {
                status_header(400);
            }

            wp_die();
        }

        /**
         * Inserts a video post into the WordPress database after an upload is completed.
         *
         * @mvc Controller
         */
		public function upload_complete() {
            $data = $_POST['data'];
            $filename = esc_attr( $data['filename'] );
            $url = '';
            $content = '';

            if ( is_user_logged_in() ) {
                $user = get_current_user_id();
            } else {
                $user = 1;
            }

            $kind = esc_attr( $data['kind'] );
            switch( $kind ) {
                case 'youtube':
                    if ($data['url']) {
                        $url = esc_url( $data['url'] );
                    }
                    $content = $url;
                    break;
                case 'dropbox':
                case 'azure':
                    return null;
                case 's3':
                    $region = $this->modules['CCB_Settings']->settings['s3']['field-s3-region'];

                    $prefix = 'https://s3';
                    if ($region !== 'us-east-1') {
                        $prefix .= '-' . $region;
                    }
                    $prefix .= '.amazonaws.com/';

                    $bucket = $this->modules['CCB_Settings']->settings['s3']['field-s3-bucket'];
                    $key = esc_attr( $data['key'] );
                    $url = $prefix . $bucket . '/' . $key;
                    $content = $url;
                    break;
                case 'gdrive':
                    $id = esc_attr( $data['id'] );
                    $url = 'https://drive.google.com/uc?export=download&id=' . $id;
                    $content = '<video controls src="' . $url . '" width="100%"></video>';
                    break;
                case 'blob':
                    $url = esc_url( $data['data'][0] );
                    $content = $url;
                    break;
            }

            if ( $data['post_content'] ) {
                $data['post_content'] = '<p>' . $content . '</p><p>' . $data['post_content'] . '</p>';
            }

            $post_arr = shortcode_atts( array(
                'post_title'        =>  $filename,
                'post_type'         => CCB_Video_Post_Type::POST_TYPE_SLUG,
                'post_content'      => $content,
                'post_excerpt'      => '',
                'post_status'       => $this->modules['CCB_Settings']->settings['posts']['field-post-status'],
                'post_author'       => $user,
                'tax_input'         => array(
                    'category'      => $this->modules['CCB_Settings']->settings['posts']['field-post-category']
                ),
                'meta_input'        => array(
                    'ccb_video-url' => $url
                )
            ), $data );

            $post_id = wp_insert_post( $post_arr );

            wp_set_object_terms( $post_id, (int)$this->modules['CCB_Settings']->settings['posts']['field-post-category'], 'category' );

            echo $post_id;

            wp_die();
        }

		public function link_account() {
			$subscription = $_POST['data'];
            
			$settings = $this->modules['CCB_Settings']->settings;
			$settings['general']['field-apiKey'] = $subscription['api_key'];	
			$this->modules['CCB_Settings']->__set('settings', $settings);
			$subscription['plan']['plan_id'] = $subscription['plan_id'];
			update_option( 'ccb_plan', $subscription['plan'] );

			wp_die();
		}

		/*
		 * Instance methods
		 */

		/**
		 * Prepares sites to use the plugin during single or network-wide activation
		 *
		 * @mvc Controller
		 *
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {
			if ( $network_wide && is_multisite() ) {
				$sites = wp_get_sites( array( 'limit' => false ) );

				foreach ( $sites as $site ) {
					switch_to_blog( $site['blog_id'] );
					$this->single_activate( $network_wide );
					restore_current_blog();
				}
			} else {
				$this->single_activate( $network_wide );
			}
		}

		/**
		 * Runs activation code on a new WPMS site when it's created
		 *
		 * @mvc Controller
		 *
		 * @param int $blog_id
		 */
		public function activate_new_site( $blog_id ) {
			switch_to_blog( $blog_id );
			$this->single_activate( true );
			restore_current_blog();
		}

		/**
		 * Prepares a single blog to use the plugin
		 *
		 * @mvc Controller
		 *
		 * @param bool $network_wide
		 */
		protected function single_activate( $network_wide ) {
			foreach ( $this->modules as $module ) {
				$module->activate( $network_wide );
			}

			flush_rewrite_rules();
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 *
		 * @mvc Controller
		 */
		public function deactivate() {
			foreach ( $this->modules as $module ) {
				$module->deactivate();
			}

			delete_option( 'ccb_plan' );

			flush_rewrite_rules();
		}

		/**
		 * Register callbacks for actions and filters
		 *
		 * @mvc Controller
		 */
		public function register_hook_callbacks() {
			add_action( 'wpmu_new_blog',				        __CLASS__ . '::activate_new_site' );

			// CSS & JS
			add_action( 'wp_enqueue_scripts',			        __CLASS__ . '::load_resources' );
			add_action( 'admin_enqueue_scripts',		        __CLASS__ . '::load_resources' );

			// AJAX
            add_action( 'wp_ajax_ccb_upload_preview_available',	__CLASS__ . '::preview_available' );
            add_action( 'wp_ajax_nopriv_ccb_preview_available',	__CLASS__ . '::preview_available' );
			add_action( 'wp_ajax_ccb_upload',			        __CLASS__ . '::upload' );
			add_action( 'wp_ajax_nopriv_ccb_upload',	        __CLASS__ . '::upload' );
            add_action( 'wp_ajax_ccb_upload_complete',			array( $this, 'upload_complete' ) );
            add_action( 'wp_ajax_nopriv_ccb_upload_complete',	array( $this, 'upload_complete' ) );
            add_action( 'wp_ajax_ccb_upload_image',			    __CLASS__ . '::upload_image' );
            add_action( 'wp_ajax_nopriv_ccb_upload_image',	    __CLASS__ . '::upload_image' );
            add_action( 'wp_ajax_ccb_link_account', 			array( $this, 'link_account' ) );

			add_action( 'init',									array( $this, 'init' ) );
			add_action( 'init',									array( $this, 'upgrade' ), 11 );

			add_filter( 'the_content',							array( $this, 'append_post' ), 0 );
			add_filter( 'pre_get_posts',                		array( $this, 'show_videos_with_posts' ) );
            add_filter( 'widget_posts_args',             		array( $this, 'show_videos_in_widgets' ) );
		}

		/**
		 * Initializes variables
		 *
		 * @mvc Controller
		 */
		public function init() {
			try {
				//$instance_example = new CCB_Instance_Class( 'Instance example', '42' );
				//add_notice( $instance_example->foo .' '. $instance_example->bar );
			} catch ( Exception $exception ) {
				add_notice( __METHOD__ . ' error: ' . $exception->getMessage(), 'error' );
			}
		}

		/**
		 * Checks if the plugin was recently updated and upgrades if necessary
		 *
		 * @mvc Controller
		 *
		 * @param int|string $db_version
		 */
		public function upgrade( $db_version = 0 ) {
			$version = get_option( 'ccb_version', '0' );
			if ( version_compare( $version, self::VERSION, '==' ) ) {
				return;
			}

			foreach ( $this->modules as $module ) {
				$module->upgrade( $version );
			}

			update_option( 'ccb_version', self::VERSION );
			self::clear_caching_plugins();
		}

		/**
		 * Checks that the object is in a correct state
		 *
		 * @mvc Model
		 *
		 * @param string $property An individual property to check, or 'all' to check all of them
		 * @return bool
		 */
		protected function is_valid( $property = 'all' ) {
			return true;
		}

		/**
		 * Appends the shortcode to the end of each post.
		 *
		 * @mvc Model
		 *
		 * @param string $content
		 * @return string
		 */
		public function append_post( $content ) {
			$included_post_types = $this->modules['CCB_Settings']->settings['general']['field-appendPost'];
			if ( is_singular() && in_array( get_post_type(), $included_post_types ) ) {
				$content .= '[clipchamp]';
			}
			return $content;
		}

        /**
         * Adds video post type to WP queries.
         *
         * @mvc Model
         *
         * @param array $query
         * @return array
         */
		public function show_videos_with_posts($query) {
		    $show = $this->modules['CCB_Settings']->settings['posts']['field-show-with-posts'];
		    if ( $show ) {
                if ( ( ( is_home() || is_category() ) && $query->is_main_query() ) )
                    $query->set( 'post_type', array( 'post', CCB_Video_Post_Type::POST_TYPE_SLUG ) );
            }
            return $query;
        }

        /**
         * Adds video post type to widget queries.
         *
         * @param array $params
         * @return array
         */
        public function show_videos_in_widgets($params) {
            $show = $this->modules['CCB_Settings']->settings['posts']['field-show-with-posts'];
            if ( $show ) {
                $params['post_type'] = array( 'post', CCB_Video_Post_Type::POST_TYPE_SLUG );
            }
            return $params;
        }
	} // end Clipchamp
}