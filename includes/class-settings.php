<?php
class Plugin_Settings {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
    }

    public function add_plugin_settings_page() {
        add_menu_page(
            'Product Carousel Settings',
            'Carousel Settings',
            'manage_options',
            'product-carousel-settings',
            array( $this, 'display_plugin_settings_page' ),
            'dashicons-admin-generic',
            20
        );
    }

    public function display_plugin_settings_page() {
        ?>
        <div class="wrap">
            <h1>Product Carousel Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'plugin-settings-group' );
                do_settings_sections( 'plugin-settings-group' );
                ?>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row">Allowed Domains</th>
                    <td><input type="text" name="allowed_domains" value="<?php echo esc_attr( get_option('allowed_domains') ); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function register_plugin_settings() {
        register_setting( 'plugin-settings-group', 'allowed_domains' );
    }
}
