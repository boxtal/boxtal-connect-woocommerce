<?php
/**
 * Contains code for the notices class.
 *
 * @package     Boxtal\BoxtalWoocommerce\Admin
 */

namespace Boxtal\BoxtalWoocommerce\Admin;

/**
 * Notices class.
 *
 * Display notices if some are to be displayed.
 *
 * @class       Notices
 * @package     Boxtal\BoxtalWoocommerce\Admin
 * @category    Class
 * @author      API Boxtal
 */
class Notices {

	/**
	 * Array of notices - name => callback.
	 *
	 * @var array
	 */
	private static $core_notices = array('pair', 'update', 'install');

	/**
	 * Plugin url.
	 *
	 * @var string
	 */
	private static $plugin_url;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	private static $plugin_version;

	/**
	 * Construct function.
	 *
	 * @param array $plugin plugin array.
	 * @void
	 */
	public function __construct( $plugin ) {
		self::$plugin_url     = $plugin['url'];
		self::$plugin_version = $plugin['version'];
	}

	/**
	 * Run class.
	 *
	 * @void
	 */
	public function run() {
		$notices = $this->get_notices();

		if ( ! empty( $notices ) ) {
			foreach ( $notices as $notice ) {
				add_action( 'admin_notices', array( $notice, 'render' ) );
				wp_enqueue_style( 'bw_notices_css', self::$plugin_url . 'Boxtal/BoxtalWoocommerce/assets/css/notices.css', array(), self::$plugin_version );
			}
		}
	}

	/**
	 * Get notices.
	 *
	 * @return mixed $notices instances of notice.
	 */
	public function get_notices() {
		$notices          = get_option( 'BW_NOTICES', array() );
		$notice_instances = array();
		foreach ( $notices as $key ) {
			$classname = 'Boxtal\BoxtalWoocommerce\Notices\\';
			if ( ! in_array( $key, self::$core_notices, true ) ) {
				$notice = get_transient( $key );
				if ( false !== $notice && isset( $notice['type'] ) ) {
					$classname .= ucfirst( $notice['type'] ) . '_Notice';
					if ( class_exists( $classname, true ) ) {
						$class              = new $classname( $key, $notice );
						$notice_instances[] = $class;
					}
				} else {
					self::remove_notice( $key );
				}
			} else {
				$classname .= ucfirst( $key ) . '_Notice';
				if ( class_exists( $classname, true ) ) {
					$class              = new $classname( $key );
					$notice_instances[] = $class;
				}
			}
		}
		return $notice_instances;
	}

	/**
	 * Add notice.
	 *
	 * @param string $type type of notice.
	 * @param mixed  $args additional args.
	 * @void
	 */
	public function add_notice( $type, $args = array() ) {
		if ( ! in_array( $type, self::$core_notices, true ) ) {
			$key           = uniqid( 'bw_', false );
			$value         = $args;
			$value['type'] = $type;
			set_transient( $key, $value, DAY_IN_SECONDS );
		} else {
			$key = $type;
		}
		$notices   = get_option( 'BW_NOTICES', array() );
		$notices[] = $key;
		update_option( 'BW_NOTICES', $notices );
	}

	/**
	 * Remove notice.
	 *
	 * @param string $key notice key.
	 * @void
	 */
	public static function remove_notice( $key ) {
		$notices = get_option( 'BW_NOTICES', array() );
        // phpcs:ignore
		if ( ( $index = array_search( $key, $notices, true ) ) !== false ) {
			unset( $notices[ $index ] );
		}
		update_option( 'BW_NOTICES', $notices );
	}
}
