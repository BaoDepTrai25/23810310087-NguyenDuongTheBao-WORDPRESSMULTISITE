<?php
/**
 * Plugin Name:       LearnPress Stats Dashboard
 * Plugin URI:        https://example.com/learnpress-stats-dashboard
 * Description:       Hiển thị thống kê LearnPress trong Dashboard Widget và bằng shortcode.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            OpenAI
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       lp-stats-addon
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'LP_Stats_Addon' ) ) {

    class LP_Stats_Addon {

        public function __construct() {
            add_action( 'wp_dashboard_setup', array( $this, 'register_dashboard_widget' ) );
            add_shortcode( 'lp_total_stats', array( $this, 'render_shortcode' ) );
        }

        /**
         * Kiểm tra LearnPress có đang hoạt động không.
         */
        private function is_learnpress_active() {
            return class_exists( 'LearnPress' ) || defined( 'LEARNPRESS_VERSION' );
        }

        /**
         * Lấy dữ liệu thống kê chính.
         */
        private function get_stats() {
            global $wpdb;

            $stats = array(
                'total_courses'    => 0,
                'total_students'   => 0,
                'completed_courses'=> 0,
            );

            // 1) Tổng số khóa học hiện có.
            $stats['total_courses'] = (int) wp_count_posts( 'lp_course' )->publish;

            if ( ! $this->is_learnpress_active() ) {
                return $stats;
            }

            $table_user_items = $wpdb->prefix . 'learnpress_user_items';
            $table_posts      = $wpdb->posts;

            // 2) Tổng số học viên đã đăng ký.
            // Đếm DISTINCT user_id trên các bản ghi gắn với course.
            $stats['total_students'] = (int) $wpdb->get_var(
                "SELECT COUNT(DISTINCT ui.user_id)
                 FROM {$table_user_items} ui
                 INNER JOIN {$table_posts} p ON ui.item_id = p.ID
                 WHERE p.post_type = 'lp_course'
                   AND ui.user_id IS NOT NULL
                   AND ui.user_id > 0"
            );

            // 3) Số lượng khóa học đã hoàn thành.
            // Đếm số bản ghi course có trạng thái completed.
            $stats['completed_courses'] = (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*)
                     FROM {$table_user_items} ui
                     INNER JOIN {$table_posts} p ON ui.item_id = p.ID
                     WHERE p.post_type = 'lp_course'
                       AND ui.status = %s",
                    'completed'
                )
            );

            return $stats;
        }

        /**
         * Đăng ký Dashboard Widget.
         */
        public function register_dashboard_widget() {
            if ( ! current_user_can( 'manage_options' ) ) {
                return;
            }

            wp_add_dashboard_widget(
                'lp_stats_dashboard_widget',
                'LearnPress Stats Dashboard',
                array( $this, 'render_dashboard_widget' )
            );
        }

        /**
         * Giao diện bảng thống kê dùng chung.
         */
        private function get_stats_html() {
            $stats = $this->get_stats();
            ob_start();
            ?>
            <div class="lp-stats-addon-wrap">
                <style>
                    .lp-stats-addon-wrap{font-family:Arial,sans-serif}
                    .lp-stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-top:12px}
                    .lp-stat-card{background:#fff;border:1px solid #dcdcde;border-left:4px solid #2271b1;border-radius:8px;padding:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)}
                    .lp-stat-label{font-size:13px;color:#50575e;margin-bottom:8px}
                    .lp-stat-number{font-size:28px;font-weight:700;color:#1d2327;line-height:1.2}
                    .lp-stat-note{margin-top:10px;color:#646970;font-size:12px}
                </style>

                <div class="lp-stats-grid">
                    <div class="lp-stat-card">
                        <div class="lp-stat-label">Tổng số khóa học</div>
                        <div class="lp-stat-number"><?php echo esc_html( $stats['total_courses'] ); ?></div>
                    </div>
                    <div class="lp-stat-card">
                        <div class="lp-stat-label">Tổng số học viên đã đăng ký</div>
                        <div class="lp-stat-number"><?php echo esc_html( $stats['total_students'] ); ?></div>
                    </div>
                    <div class="lp-stat-card">
                        <div class="lp-stat-label">Số khóa học đã hoàn thành</div>
                        <div class="lp-stat-number"><?php echo esc_html( $stats['completed_courses'] ); ?></div>
                    </div>
                </div>

                <div class="lp-stat-note">
                    Shortcode sử dụng: <code>[lp_total_stats]</code>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * Render widget trong Dashboard.
         */
        public function render_dashboard_widget() {
            echo $this->get_stats_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /**
         * Render shortcode ngoài frontend.
         */
        public function render_shortcode() {
            return $this->get_stats_html();
        }
    }

    new LP_Stats_Addon();
}
