<?php
/**
 * Local automation handler for ClickRank.ai requests.
 * Provides offline-friendly behaviour without any external dependencies.
 *
 * @link       https://clickrank.ai/
 * @since      3.2.0
 *
 * @package    ClickRank_AI
 * @subpackage ClickRank_AI/includes/api
 */
class ClickRank_AI_API_Sender {

        /**
         * Register the current site as active.
         *
         * @return bool Always true in local mode.
         */
        public static function send_subscription( $api_key = null ) {
                ClickRank_AI_Logger::info( 'Local mode: subscription handshake skipped.' );
                return true;
        }

        /**
         * Synchronise stored SEO data with WordPress content.
         *
         * @return bool True when the local refresh completes.
         */
        public static function sync_data( $api_key = null ) {
                global $wpdb;

                $table = $wpdb->prefix . 'clickrank_ai_seo_data';

                $table_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
                if ( $table_exists !== $table ) {
                        ClickRank_AI_Logger::warning( 'Local sync skipped: SEO data table is missing.' );
                        return false;
                }

                $entries = $wpdb->get_results( "SELECT * FROM {$table}" );

                if ( empty( $entries ) ) {
                        ClickRank_AI_Logger::info( 'Local sync completed: no stored optimisations found.' );
                        return true;
                }

                $processed = 0;
                $updated   = 0;

                foreach ( $entries as $entry ) {
                        $processed++;

                        $payload = [
                                'page_url'         => $entry->page_url,
                                'page_title'       => $entry->page_title,
                                'meta_description' => $entry->meta_description,
                                'page_schema'      => $entry->page_schema,
                                'canonical_url'    => $entry->canonical_url,
                        ];

                        if ( self::apply_optimizations( $payload ) ) {
                                $updated++;
                        }
                }

                ClickRank_AI_Logger::info( 'Local sync completed', [
                        'processed' => $processed,
                        'updated'   => $updated,
                ] );

                return ( 0 === $processed ) ? true : ( $updated > 0 );
        }

        /**
         * Test connection in local mode.
         */
        public static function test_connection( $api_key = null ) {
                ClickRank_AI_Logger::debug( 'Local mode: connection test always succeeds.' );
                set_transient( 'clickrank_ai_last_successful_connection', time(), DAY_IN_SECONDS );

                return [ 'success' => true, 'message' => 'Local mode active' ];
        }

        /**
         * Process sync response data
         */
        private static function process_sync_data( $data ) {
                $processed  = 0;
                $successful = 0;

                foreach ( $data as $page_url => $optimizations ) {
                        $processed++;

                        if ( empty( $optimizations['page_url'] ) ) {
                                $optimizations['page_url'] = $page_url;
                        }

                        if ( self::apply_optimizations( $optimizations ) ) {
                                $successful++;
                        }
                }

                ClickRank_AI_Logger::info( "Sync complete: {$successful}/{$processed} pages updated" );
                return $successful > 0;
        }

        /**
         * Apply optimizations to content
         */
        private static function apply_optimizations( $data ) {
                if ( empty( $data['page_url'] ) ) {
                        return false;
                }

                $page_url = $data['page_url'];
                $content  = self::resolve_url( $page_url );

                if ( ! $content ) {
                        // Try image-only update
                        if ( ! empty( $data['image_optimizations'] ) ) {
                                return self::update_images( $data['image_optimizations'] );
                        }
                        ClickRank_AI_Logger::warning( "Cannot resolve URL: {$page_url}" );
                        return false;
                }

                switch ( $content['type'] ) {
                        case 'homepage':
                                return self::update_homepage( $data );
                        case 'post':
                                return self::update_post( $content['id'], $data );
                        default:
                                ClickRank_AI_Logger::warning( "Unsupported content type: {$content['type']}" );
                                return false;
                }
        }

        /**
         * Simple URL resolution
         */
        private static function resolve_url( $url ) {
                $path = wp_parse_url( $url, PHP_URL_PATH ) ?: '/';

                // Homepage
                if ( rtrim( $path, '/' ) === rtrim( wp_parse_url( home_url(), PHP_URL_PATH ) ?: '', '/' ) ) {
                        return [ 'type' => 'homepage' ];
                }

                // Post/page
                $post_id = url_to_postid( $url );
                if ( $post_id ) {
                        return [ 'type' => 'post', 'id' => $post_id ];
                }

                return null;
        }

        /**
         * Update homepage
         */
        private static function update_homepage( $data ) {
                if ( ! class_exists( 'ClickRank_AI_SEO_Compat' ) ) {
                        require_once CLICKRANK_AI_PLUGIN_DIR . 'includes/utils/class-clickrank-ai-seo-compat.php';
                }

                $compat  = new ClickRank_AI_SEO_Compat();
                $updated = 0;

                // PHASE 1: Write to URL-based table (sync with webhook behavior)
                $url_data = [];
                if ( ! empty( $data['page_title'] ) ) {
                        $url_data['page_title'] = sanitize_text_field( $data['page_title'] );
                }
                if ( ! empty( $data['meta_description'] ) ) {
                        $url_data['meta_description'] = sanitize_text_field( $data['meta_description'] );
                }
                if ( ! empty( $data['page_schema'] ) ) {
                        $url_data['page_schema'] = wp_kses_post( $data['page_schema'] );
                }
                if ( ! empty( $data['canonical_url'] ) ) {
                        $url_data['canonical_url'] = esc_url_raw( $data['canonical_url'] );
                }
                if ( ! empty( $url_data ) ) {
                        ClickRank_AI_SEO_Data_Manager::save_seo_data( home_url(), $url_data, true );
                        ClickRank_AI_Logger::debug( 'Sync: Homepage data saved to URL table' );
                }

                // Continue with post meta writes (backward compatibility)
                if ( get_option( 'clickrank_ai_enable_title_opt' ) && ! empty( $data['page_title'] ) ) {
                        $compat->update_homepage_title( sanitize_text_field( $data['page_title'] ) );
                        $updated++;
                }

                if ( get_option( 'clickrank_ai_enable_meta_opt' ) && ! empty( $data['meta_description'] ) ) {
                        $compat->update_homepage_description( sanitize_text_field( $data['meta_description'] ) );
                        $updated++;
                }

                if ( $updated > 0 ) {
                        ClickRank_AI_Logger::info( "Homepage updated: {$updated} fields" );
                        return true;
                }

                return false;
        }

        /**
         * Update post/page
         */
        private static function update_post( $post_id, $data ) {
                $post = get_post( $post_id );
                if ( ! $post || $post->post_status !== 'publish' ) {
                        return false;
                }

                if ( ! class_exists( 'ClickRank_AI_SEO_Compat' ) ) {
                        require_once CLICKRANK_AI_PLUGIN_DIR . 'includes/utils/class-clickrank-ai-seo-compat.php';
                }

                $compat      = new ClickRank_AI_SEO_Compat();
                $updated     = 0;
                $revert_data = [];

                // PHASE 1: Write to URL-based table (sync with webhook behavior)
                $url_data = [];
                if ( ! empty( $data['page_title'] ) ) {
                        $url_data['page_title'] = sanitize_text_field( $data['page_title'] );
                }
                if ( ! empty( $data['meta_description'] ) ) {
                        $url_data['meta_description'] = sanitize_text_field( $data['meta_description'] );
                }
                if ( ! empty( $data['page_schema'] ) ) {
                        $url_data['page_schema'] = wp_kses_post( $data['page_schema'] );
                }
                if ( ! empty( $data['canonical_url'] ) ) {
                        $url_data['canonical_url'] = esc_url_raw( $data['canonical_url'] );
                }
                if ( ! empty( $url_data ) ) {
                        $post_url = get_permalink( $post_id );
                        ClickRank_AI_SEO_Data_Manager::save_seo_data( $post_url, $url_data, true );
                        ClickRank_AI_Logger::debug( "Sync: Post {$post_id} data saved to URL table" );
                }

                // Continue with post meta writes (backward compatibility)
                // Title
                if ( get_option( 'clickrank_ai_enable_title_opt' ) && ! empty( $data['page_title'] ) ) {
                        $meta_key                 = $compat->get_seo_meta_key( 'title' );
                        $revert_data['page_title'] = get_post_meta( $post_id, $meta_key, true );
                        update_post_meta( $post_id, $meta_key, sanitize_text_field( $data['page_title'] ) );
                        $updated++;
                }

                // Description
                if ( get_option( 'clickrank_ai_enable_meta_opt' ) && ! empty( $data['meta_description'] ) ) {
                        $meta_key                          = $compat->get_seo_meta_key( 'description' );
                        $revert_data['meta_description'] = get_post_meta( $post_id, $meta_key, true );
                        update_post_meta( $post_id, $meta_key, sanitize_text_field( $data['meta_description'] ) );
                        $updated++;
                }

                // Canonical
                if ( get_option( 'clickrank_ai_enable_canonical_opt' ) && ! empty( $data['canonical_url'] ) ) {
                        $revert_data['canonical_url'] = get_post_meta( $post_id, '_clickrank_ai_canonical_url', true );
                        update_post_meta( $post_id, '_clickrank_ai_canonical_url', esc_url_raw( $data['canonical_url'] ) );
                        $updated++;
                }

                // Schema
                if ( get_option( 'clickrank_ai_enable_schema_opt' ) && ! empty( $data['page_schema'] ) ) {
                        $revert_data['page_schema'] = get_post_meta( $post_id, '_clickrank_ai_page_schema', true );
                        update_post_meta( $post_id, '_clickrank_ai_page_schema', wp_kses_post( $data['page_schema'] ) );
                        $updated++;
                }

                // Images
                if ( get_option( 'clickrank_ai_enable_img_alt_opt' ) && ! empty( $data['image_optimizations'] ) ) {
                        if ( self::update_images( $data['image_optimizations'] ) ) {
                                $updated++;
                        }
                }

                // Save revert data
                if ( ! empty( $revert_data ) ) {
                        update_post_meta( $post_id, '_clickrank_ai_revert_data', $revert_data );
                }

                if ( $updated > 0 ) {
                        ClickRank_AI_Logger::info( "Post {$post_id} updated: {$updated} fields" );
                        return true;
                }

                return false;
        }

        /**
         * Update images
         */
        private static function update_images( $images ) {
                $updated = 0;

                foreach ( $images as $img ) {
                        if ( empty( $img['image_url'] ) ) {
                                continue;
                        }

                        $attachment_id = attachment_url_to_postid( $img['image_url'] );
                        if ( ! $attachment_id ) {
                                continue;
                        }

                        if ( isset( $img['new_alt_text'] ) ) {
                                update_post_meta( $attachment_id, '_wp_attachment_image_alt', sanitize_text_field( $img['new_alt_text'] ) );
                                $updated++;
                        }

                        if ( isset( $img['new_title'] ) ) {
                                wp_update_post( [ 'ID' => $attachment_id, 'post_title' => sanitize_text_field( $img['new_title'] ) ] );
                                $updated++;
                        }
                }

                return $updated > 0;
        }
}
