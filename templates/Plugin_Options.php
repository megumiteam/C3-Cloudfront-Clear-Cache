<?php

use C3_CloudFront_Cache_Controller\Constants;
use C3_CloudFront_Cache_Controller\WP\Environment;
use C3_CloudFront_Cache_Controller\WP\Options;

$env = new Environment();
if ( $env->is_amimoto_managed () ) {
    /**
     * In AMIMOTO Managed, all configuration is already configured by our team.
     */
    return;
}

$env_distribution_id = $env->get_distribution_id();
$env_access_key = $env->get_aws_access_key();
$env_secret_key = $env->get_aws_secret_key();
$text_domain = Constants::text_domain();
$options = new Options();
$plugin_options = $options->get_options();
$distribution_id = isset( $plugin_options ) && isset( $plugin_options[ Constants::DISTRIBUTION_ID ] ) ? $plugin_options[ Constants::DISTRIBUTION_ID ] : null;
$access_key = isset( $plugin_options ) && isset( $plugin_options[ Constants::ACCESS_KEY ] ) ? $plugin_options[ Constants::ACCESS_KEY ] : null;
$secret_key = isset( $plugin_options ) && isset( $plugin_options[ Constants::SECRET_KEY ] ) ? $plugin_options[ Constants::SECRET_KEY ] : null;

?>
<h3><?php _e( 'General Settings', $text_domain ) ?></h3>
<form method="post" action="options.php">
    <table class='widefat form-table'><tbody>
        <tr>
            <td>
                <?php _e( 'CloudFront Distribution ID', $text_domain ); ?>
            </td>
            <td>
                <input
                    class='regular-text code'
                    type="text"
                    name="<?php echo isset( $env_distribution_id ) ? Constants::OPTION_NAME . '[dummy]' : Constants::OPTION_NAME . '[' . Constants::DISTRIBUTION_ID .']' ; ?>"
                    value="<?php echo esc_attr( isset($env_distribution_id) ? $env_distribution_id : $distribution_id ); ?>"
                    <?php echo isset( $env_distribution_id ) ? 'disabled' : ''; ?>
                />
                <?php
                    if ( isset( $env_distribution_id ) ) {
                        echo "<p><small>" . __( 'Already defined it in the code.', $text_domain ) . '</small></p>';
                    }

                ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php _e( 'AWS Access Key', $text_domain ); ?>
            </td>
            <td>
                <input
                    class='regular-text code'
                    type="password"
                    name="<?php echo isset( $env_access_key ) ? Constants::OPTION_NAME . '[dummy1]' : Constants::OPTION_NAME . '[' . Constants::ACCESS_KEY . ']' ; ?>"
                    value="<?php echo esc_attr( isset($env_access_key) ? $env_access_key : $access_key ); ?>"
                    <?php echo isset( $env_access_key ) ? "disabled" : ""; ?>
                />
                <?php
                    if ( isset( $env_access_key ) ) {
                        echo "<p><small>" . __( 'Already defined it in the code.', $text_domain ) . '</small></p>';
                    }

                ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php _e( 'AWS Secret Key', $text_domain ); ?>
            </td>
            <td>
                <input
                    class='regular-text code'
                    type="password"
                    name="<?php echo isset( $env_secret_key ) ? Constants::OPTION_NAME . '[dummy2]' : Constants::OPTION_NAME . '[' . Constants::SECRET_KEY . ']' ; ?>"
                    value="<?php echo esc_attr( isset($env_secret_key) ? $env_secret_key : $secret_key ); ?>"
                    <?php echo isset( $env_secret_key ) ? "disabled" : ""; ?>
                />
                <?php
                    if ( isset( $env_secret_key ) ) {
                        echo "<p><small>" . __( 'Already defined it in the code.', $text_domain ) . '</small></p>';
                    }

                ?>
            </td>
        </tr>
    </tbody></table>
    <?php if ( ! isset( $env_distribution_id ) || ! isset( $env_secret_key ) || ! isset( $env_secret_key ) ) {
        settings_fields( Constants::MENU_ID );
        submit_button();
    }
    ?>
</form>