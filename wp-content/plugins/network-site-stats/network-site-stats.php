<?php
/*
Plugin Name: Network Site Stats
Description: Thống kê danh sách các site trong WordPress Multisite.
Version: 1.0
Author: Nguyen Bao
Network: true
*/

if (!defined('ABSPATH')) {
    exit;
}

function nss_add_network_menu() {
    add_menu_page(
        'Network Site Stats',
        'Network Site Stats',
        'manage_network',
        'network-site-stats',
        'nss_render_network_stats_page',
        'dashicons-chart-bar',
        30
    );
}
add_action('network_admin_menu', 'nss_add_network_menu');

function nss_get_post_count_for_blog() {
    $count_posts = wp_count_posts('post');
    return isset($count_posts->publish) ? (int) $count_posts->publish : 0;
}

function nss_get_latest_post_date_for_blog() {
    $latest_post = get_posts(array(
        'numberposts' => 1,
        'post_status' => 'publish',
        'post_type'   => 'post',
        'orderby'     => 'date',
        'order'       => 'DESC',
    ));

    if (!empty($latest_post)) {
        return get_the_date('d/m/Y H:i:s', $latest_post[0]->ID);
    }

    return 'Chưa có bài viết';
}

function nss_render_network_stats_page() {
    if (!current_user_can('manage_network')) {
        return;
    }

    $sites = get_sites();

    echo '<div class="wrap">';
    echo '<h1>Network Site Stats</h1>';
    echo '<p>Danh sách các site trong hệ thống Multisite.</p>';

    echo '<table class="widefat fixed striped">';
    echo '<thead>
            <tr>
                <th>ID Site</th>
                <th>Tên Site</th>
                <th>URL</th>
                <th>Số bài viết</th>
                <th>Bài viết mới nhất</th>
            </tr>
          </thead>';
    echo '<tbody>';

    if (!empty($sites)) {
        foreach ($sites as $site) {
            switch_to_blog($site->blog_id);

            $blog_name   = get_bloginfo('name');
            $blog_url    = get_site_url();
            $post_count  = nss_get_post_count_for_blog();
            $latest_post = nss_get_latest_post_date_for_blog();

            echo '<tr>';
            echo '<td>' . esc_html($site->blog_id) . '</td>';
            echo '<td>' . esc_html($blog_name) . '</td>';
            echo '<td><a href="' . esc_url($blog_url) . '" target="_blank">' . esc_html($blog_url) . '</a></td>';
            echo '<td>' . esc_html($post_count) . '</td>';
            echo '<td>' . esc_html($latest_post) . '</td>';
            echo '</tr>';

            restore_current_blog();
        }
    } else {
        echo '<tr><td colspan="5">Không có site nào trong hệ thống.</td></tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}