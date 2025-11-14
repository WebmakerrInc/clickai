<?php
/**
 * Provides the view for the Home tab in the admin dashboard with the new UI/UX.
 * Local mode always presents the active dashboard.
 *
 * @link       https://clickrank.ai/
 * @since      3.1.0
 *
 * @package    ClickRank_AI
 * @subpackage ClickRank_AI/includes/admin/views
 */

if ( ! defined( 'WPINC' ) ) {
        die;
}

$module_keys = [
        'clickrank_ai_enable_title_opt',
        'clickrank_ai_enable_meta_opt',
        'clickrank_ai_enable_img_alt_opt',
        'clickrank_ai_enable_schema_opt',
        'clickrank_ai_enable_canonical_opt',
        'clickrank_ai_enable_link_title_opt',
];
$active_modules = 0;
foreach ( $module_keys as $key ) {
        if ( get_option( $key ) ) {
                $active_modules++;
        }
}
$total_modules = count( $module_keys );
$local_mode    = (bool) get_option( 'clickrank_ai_local_mode', true );
$last_health   = get_transient( 'clickrank_ai_last_health_check' );

$sync_button_classes = 'w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-md border border-transparent py-3 px-5 text-sm font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2';
$sync_button_classes .= $local_mode ? ' bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500' : ' bg-gray-200 text-gray-500 cursor-not-allowed focus:ring-gray-300';
$sync_button_attributes = $local_mode ? '' : ' disabled="disabled"';
$sync_button_label      = $local_mode ? __( 'Run Local Sync', 'clickrank-ai' ) : __( 'Enable Local Mode to Sync', 'clickrank-ai' );

?>
<div class="space-y-8">
        <!-- Dashboard Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                        <h2 class="text-3xl font-bold text-gray-900"><?php esc_html_e( 'Dashboard', 'clickrank-ai' ); ?></h2>
                        <p class="text-lg text-gray-600 mt-1"><?php esc_html_e( 'All automation features are ready to use immediately—no account required.', 'clickrank-ai' ); ?></p>
                </div>
                <div class="flex-shrink-0">
                        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                                <input type="hidden" name="action" value="clickrank_ai_sync_data">
                                <?php wp_nonce_field( 'clickrank_ai_sync_data_nonce' ); ?>
                                <button type="submit" class="<?php echo esc_attr( $sync_button_classes ); ?>"<?php echo $sync_button_attributes; ?>>
                                        <i class="fas fa-sync-alt"></i>
                                        <?php echo esc_html( $sync_button_label ); ?>
                                </button>
                        </form>
                </div>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                        <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 text-green-600 rounded-full h-12 w-12 flex items-center justify-center text-xl">
                                        <i class="fas fa-power-off"></i>
                                </div>
                                <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Automation Mode', 'clickrank-ai' ); ?></p>
                                        <p class="text-2xl font-bold text-gray-900"><?php echo $local_mode ? esc_html__( 'Local & Unlocked', 'clickrank-ai' ) : esc_html__( 'Local Mode Off', 'clickrank-ai' ); ?></p>
                                        <?php if ( ! $local_mode ) : ?>
                                                <p class="text-xs text-red-600 mt-1"><?php esc_html_e( 'Enable local mode in Settings to resume automated updates.', 'clickrank-ai' ); ?></p>
                                        <?php endif; ?>
                                </div>
                        </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                        <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 text-blue-600 rounded-full h-12 w-12 flex items-center justify-center text-xl">
                                        <i class="fas fa-puzzle-piece"></i>
                                </div>
                                <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Active Modules', 'clickrank-ai' ); ?></p>
                                        <p class="text-2xl font-bold text-gray-900"><?php echo absint( $active_modules ); ?> / <?php echo absint( $total_modules ); ?></p>
                                </div>
                        </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                        <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-100 text-yellow-600 rounded-full h-12 w-12 flex items-center justify-center text-xl">
                                         <i class="fas fa-heartbeat"></i>
                                </div>
                                <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Last Health Check', 'clickrank-ai' ); ?></p>
                                        <?php if ( $last_health ) : ?>
                                                <p class="text-sm font-semibold text-gray-900"><?php echo esc_html( human_time_diff( $last_health, time() ) ); ?> <?php esc_html_e( 'ago', 'clickrank-ai' ); ?></p>
                                        <?php else : ?>
                                                <p class="text-sm font-semibold text-gray-900"><?php esc_html_e( 'Pending first run', 'clickrank-ai' ); ?></p>
                                        <?php endif; ?>
                                </div>
                        </div>
                </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white p-8 rounded-lg shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900 mb-4"><?php esc_html_e( 'Getting the Most from Local Automation', 'clickrank-ai' ); ?></h3>
                        <p class="text-gray-600 mb-6"><?php esc_html_e( 'Optimisations are handled entirely on your site. Use these quick tips to stay organised and in control.', 'clickrank-ai' ); ?></p>
                        <ul class="space-y-5">
                                <li class="flex items-start">
                                        <div class="flex-shrink-0 bg-blue-100 text-blue-600 rounded-full h-8 w-8 flex items-center justify-center font-bold">1</div>
                                        <div class="ml-4">
                                                <h4 class="font-semibold text-gray-800"><?php esc_html_e( 'Review module toggles regularly', 'clickrank-ai' ); ?></h4>
                                                <p class="text-gray-600"><?php esc_html_e( 'Head to the Settings tab to enable or disable individual automation modules as your SEO strategy evolves.', 'clickrank-ai' ); ?></p>
                                        </div>
                                </li>
                                <li class="flex items-start">
                                        <div class="flex-shrink-0 bg-blue-100 text-blue-600 rounded-full h-8 w-8 flex items-center justify-center font-bold">2</div>
                                        <div class="ml-4">
                                                <h4 class="font-semibold text-gray-800"><?php esc_html_e( 'Run manual syncs after bulk edits', 'clickrank-ai' ); ?></h4>
                                                <p class="text-gray-600"><?php esc_html_e( 'Use the “Run Local Sync” button whenever you make large content updates to refresh stored SEO data.', 'clickrank-ai' ); ?></p>
                                        </div>
                                </li>
                                <li class="flex items-start">
                                        <div class="flex-shrink-0 bg-blue-100 text-blue-600 rounded-full h-8 w-8 flex items-center justify-center font-bold">3</div>
                                        <div class="ml-4">
                                                <h4 class="font-semibold text-gray-800"><?php esc_html_e( 'Audit the activity log', 'clickrank-ai' ); ?></h4>
                                                <p class="text-gray-600"><?php esc_html_e( 'The Logs tab records every optimisation performed locally so you have a complete change history.', 'clickrank-ai' ); ?></p>
                                        </div>
                                </li>
                        </ul>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900 mb-4"><?php esc_html_e( 'Quick Links', 'clickrank-ai' ); ?></h3>
                        <div class="space-y-4">
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . CLICKRANK_AI_MENU_SLUG . '&tab=settings' ) ); ?>" class="group flex items-center p-3 rounded-md bg-gray-50 hover:bg-blue-50 transition-colors">
                                        <div class="bg-blue-100 text-blue-600 rounded-lg p-2 text-xl"><i class="fas fa-cogs fa-fw"></i></div>
                                        <span class="ml-4 font-semibold text-gray-700 group-hover:text-blue-700"><?php esc_html_e( 'Configure Settings', 'clickrank-ai' ); ?></span>
                                </a>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . CLICKRANK_AI_MENU_SLUG . '&tab=logs' ) ); ?>" class="group flex items-center p-3 rounded-md bg-gray-50 hover:bg-blue-50 transition-colors">
                                        <div class="bg-blue-100 text-blue-600 rounded-lg p-2 text-xl"><i class="fas fa-clipboard-list fa-fw"></i></div>
                                        <span class="ml-4 font-semibold text-gray-700 group-hover:text-blue-700"><?php esc_html_e( 'View Activity Logs', 'clickrank-ai' ); ?></span>
                                </a>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . CLICKRANK_AI_MENU_SLUG . '&tab=settings#danger-zone' ) ); ?>" class="group flex items-center p-3 rounded-md bg-gray-50 hover:bg-blue-50 transition-colors">
                                        <div class="bg-blue-100 text-blue-600 rounded-lg p-2 text-xl"><i class="fas fa-undo fa-fw"></i></div>
                                        <span class="ml-4 font-semibold text-gray-700 group-hover:text-blue-700"><?php esc_html_e( 'Revert All Optimisations', 'clickrank-ai' ); ?></span>
                                </a>
                        </div>
                </div>
        </div>
</div>
