<?php
/**
 * Simplified settings management for ClickRank.ai plugin.
 * Essential settings functionality with streamlined validation.
 *
 * @link       https://clickrank.ai/
 * @since      3.2.0
 *
 * @package    ClickRank_AI
 * @subpackage ClickRank_AI/includes/admin
 */
class ClickRank_AI_Settings {

        const MODULES_GROUP = 'clickrank_ai_modules_group';

        public function register_settings() {
                // Module settings
                $modules = [
                        'clickrank_ai_enable_title_opt'      => 'Title optimization',
                        'clickrank_ai_enable_meta_opt'       => 'Meta description optimization',
                        'clickrank_ai_enable_img_alt_opt'    => 'Image alt text optimization',
                        'clickrank_ai_enable_schema_opt'     => 'Schema markup optimization',
                        'clickrank_ai_enable_canonical_opt'  => 'Canonical URL optimization',
                        'clickrank_ai_enable_link_title_opt' => 'Link title optimization',
                ];

                foreach ( $modules as $module => $description ) {
                        register_setting( self::MODULES_GROUP, $module, [
                                'type'              => 'boolean',
                                'sanitize_callback' => 'rest_sanitize_boolean',
                                'default'           => true,
                        ] );
                }

                register_setting( self::MODULES_GROUP, 'clickrank_ai_local_mode', [
                        'type'              => 'boolean',
                        'sanitize_callback' => 'rest_sanitize_boolean',
                        'default'           => true,
                ] );
        }

        public function check_settings_update_and_sync() {
                if ( ! current_user_can( 'manage_options' ) ) {
                        return;
                }

                if ( ! isset( $_GET['settings-updated'], $_GET['page'] ) ) {
                        return;
                }

                if ( strpos( $_GET['page'], 'clickrank-ai' ) === false ) {
                        return;
                }

                // Determine which tab was updated
                $referer_url = wp_get_referer();
                $query_args  = [];
                parse_str( wp_parse_url( $referer_url, PHP_URL_QUERY ) ?: '', $query_args );
                $tab = $query_args['tab'] ?? 'home';

                $this->handle_settings_update( $tab );
        }

        private function handle_settings_update( $tab ) {
                $redirect_url = admin_url( 'admin.php?page=' . CLICKRANK_AI_MENU_SLUG . '&tab=' . $tab );
                $redirect_url = add_query_arg( 'message', 'settings_saved', $redirect_url );

                ClickRank_AI_Logger::info( 'Settings updated', [
                        'tab'     => $tab,
                        'user_id' => get_current_user_id(),
                ] );

                wp_safe_redirect( $redirect_url );
                exit;
        }

        /**
         * Export settings
         */
        public function export_settings() {
                if ( ! current_user_can( 'manage_options' ) ) {
                        return false;
                }

                $settings = [
                        'timestamp' => time(),
                        'site_url'  => home_url(),
                        'modules'   => [],
                ];

                // Export module settings
                $modules = [
                        'clickrank_ai_enable_title_opt',
                        'clickrank_ai_enable_meta_opt',
                        'clickrank_ai_enable_img_alt_opt',
                        'clickrank_ai_enable_schema_opt',
                        'clickrank_ai_enable_canonical_opt',
                        'clickrank_ai_enable_link_title_opt',
                        'clickrank_ai_local_mode',
                ];

                foreach ( $modules as $module ) {
                        $settings['modules'][ $module ] = get_option( $module, true );
                }

                ClickRank_AI_Logger::info( 'Settings exported', [
                        'user_id'      => get_current_user_id(),
                        'modules_count'=> count( $settings['modules'] ),
                ] );

                return $settings;
        }

        /**
         * Import settings
         */
        public function import_settings( $settings ) {
                if ( ! current_user_can( 'manage_options' ) ) {
                        return [ 'success' => false, 'message' => 'Insufficient permissions' ];
                }

                if ( ! is_array( $settings ) || empty( $settings['modules'] ) ) {
                        return [ 'success' => false, 'message' => 'Invalid settings data' ];
                }

                $imported = 0;

                foreach ( $settings['modules'] as $module => $value ) {
                        if ( strpos( $module, 'clickrank_ai_enable_' ) === 0 || 'clickrank_ai_local_mode' === $module ) {
                                update_option( $module, rest_sanitize_boolean( $value ) );
                                $imported++;
                        }
                }

                ClickRank_AI_Logger::info( 'Settings imported', [
                        'user_id'       => get_current_user_id(),
                        'imported_count'=> $imported,
                ] );

                return [
                        'success'  => true,
                        'message'  => "Successfully imported {$imported} settings",
                        'imported' => $imported,
                ];
        }
}
