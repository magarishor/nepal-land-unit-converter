<?php 
/**
 * Plugin Name: Nepal Land Unit Converter
 * Plugin URI: https://github.com/magarishor/
 * Description: A plugin to convert land measurement units used in Nepal, with customizable themes and CSS.
 * Version: 2.0
 * Requires at least: 5.2
 * Requires PHP: 7.4
 * Author: Ishor Ale Magar
 * Author URI: https://github.com/magarishor
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: nepal-land-unit-converter
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class NepalLandUnitConverter {

    private $options;

    public function __construct() {
        $this->options = get_option( 'nepal_land_unit_converter_options' );
        add_action( 'admin_menu', [ $this, 'add_nluc_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_nluc_settings' ] );       
        add_action( 'init', [ $this, 'register_nluc_shortcode' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_nluc_scripts' ] );
    }
    
    public function enqueue_nluc_scripts() {      
        wp_enqueue_script( 'nepal-land-unit-converter-script', plugin_dir_url( __FILE__ ) . '/js/land-converter.js', array(), wp_get_theme()->get( 'Version' ), true );
        wp_enqueue_style( 'nepal-land-unit-converter-style', plugin_dir_url( __FILE__ ) . '/css/land-converter.css', array(), wp_get_theme()->get( 'Version' ), 'all' );
    }

    public function register_nluc_shortcode() {
        add_shortcode( 'nepal_land_unit_converter', [ $this, 'render_converter' ] );
    }

    public function render_converter() {
        ob_start();
        ?>
        <div id="land-unit-converter" class="land-converter-theme-<?php echo esc_attr( $this->options['theme'] ?? 'default' ); ?>">
            <form id="land-converter-form">
                <label for="value">Enter Value:</label>
                <input type="number" id="value" name="value" step="0.01" required>

                <label for="from-unit">From Unit:</label>
                <select id="from-unit" name="from-unit" required>
                    <option value="ropani">Ropani</option>
                    <option value="aana">Aana</option>
                    <option value="paisa">Paisa</option>
                    <option value="daam">Daam</option>
                </select>

                <label for="to-unit">To Unit:</label>
                <select id="to-unit" name="to-unit" required>
                    <option value="sqft">Square Feet</option>
                    <option value="ropani">Ropani</option>
                    <option value="aana">Aana</option>
                    <option value="paisa">Paisa</option>
                    <option value="daam">Daam</option>
                </select>

                <button type="button" id="convert-button">Convert</button>
            </form>
            <div id="conversion-result"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function add_nluc_settings_page() {
        add_options_page(
            'Nepal Land Unit Converter Settings',
            'Land Unit Converter',
            'manage_options',
            'nepal-land-unit-converter',
            [ $this, 'render_settings_page' ]
        );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Nepal Land Unit Converter Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'nepal_land_unit_converter_group' );
                do_settings_sections( 'nepal-land-unit-converter' );
                submit_button();
                ?>
            </form>
            <h4>Use this Shortcode [nepal_land_unit_converter] to diplay the form as per the need.</h4>
        </div>
        <?php
    }

    public function register_nluc_settings() {
        register_setting(
            'nepal_land_unit_converter_group',
            'nepal_land_unit_converter_options',
            [ $this, 'sanitize_settings' ]
        );

        add_settings_section(
            'nepal_land_unit_converter_main_section',
            'General Settings',
            null,
            'nepal-land-unit-converter'
        );
       
        add_settings_field(
            'theme',
            'Theme',
            [ $this, 'theme_callback' ],
            'nepal-land-unit-converter',
            'nepal_land_unit_converter_main_section'
        );
    } 

    public function theme_callback() {
        $theme = $this->options['theme'] ?? 'default';
        ?>
        <select id="theme" name="nepal_land_unit_converter_options[theme]">
            <option value="default" <?php selected( $theme, 'default' ); ?>>Default</option>
            <option value="dark" <?php selected( $theme, 'dark' ); ?>>Dark</option>
            <option value="light" <?php selected( $theme, 'light' ); ?>>Light</option>
        </select>
        <?php
    }

    public function highlight_color_callback() {
        $highlight_color = $this->options['highlight_color'] ?? '#000000';
        echo "<input type='color' id='highlight_color' name='nepal_land_unit_converter_options[highlight_color]' value='" . esc_attr( $highlight_color ) . "'>";
    }
    
}

new NepalLandUnitConverter();
?>
