<?php
/**
 * Initialize plugin functionalities
 *
 * @package Search Engine Marketing
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require 'vendor/autoload.php';

// Register a logger.
new \Sitewit\WpPlugin\Logger();

// Initialize plugin functionality.
$inc = \Sitewit\WpPlugin\Plugin::get_instance();
$inc->init_hooks();
