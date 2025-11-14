<?php
/**
 * Legacy activation view retained for backward compatibility.
 * Local mode no longer requires manual activation.
 *
 * @link       https://clickrank.ai/
 * @since      3.1.0
 */

if ( ! defined( 'WPINC' ) ) {
        die;
}
?>

<div class="bg-white shadow-sm rounded-lg p-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900"><?php esc_html_e( 'Activation Not Required', 'clickrank-ai' ); ?></h2>
        <p class="mt-4 text-gray-600">
                <?php esc_html_e( 'All ClickRank.ai features are enabled automatically in local mode. You can manage settings and modules from the main dashboard.', 'clickrank-ai' ); ?>
        </p>
        <div class="mt-6">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . CLICKRANK_AI_MENU_SLUG . '&tab=home' ) ); ?>" class="inline-flex items-center gap-2 rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left"></i>
                        <?php esc_html_e( 'Return to Dashboard', 'clickrank-ai' ); ?>
                </a>
        </div>
</div>
