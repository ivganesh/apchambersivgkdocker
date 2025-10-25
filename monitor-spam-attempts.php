<?php
/**
 * Spam Monitoring Dashboard for AP Chambers
 * View recent spam attempts and blocked registrations
 */

// Include WordPress
require_once('wp-config.php');
require_once('wp-load.php');

global $wpdb;

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Access denied.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>AP Chambers - Spam Monitoring</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .stats { display: flex; gap: 20px; margin-bottom: 30px; }
        .stat-box { background: #f0f0f0; padding: 20px; border-radius: 5px; flex: 1; }
        .stat-number { font-size: 24px; font-weight: bold; color: #d63384; }
        .stat-label { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .spam-user { background-color: #fff5f5; }
        .blocked-ip { background-color: #fff3cd; }
        .refresh-btn { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1>AP Chambers - Spam Monitoring Dashboard</h1>
        
        <button class="refresh-btn" onclick="location.reload()">Refresh</button>
        
        <div class="stats">
            <?php
            // Get recent spam users (last 30 days)
            $recent_spam = $wpdb->get_var("
                SELECT COUNT(*) FROM {$wpdb->users} 
                WHERE user_registered > DATE_SUB(NOW(), INTERVAL 30 DAY)
                AND (user_email REGEXP '\.(tk|ml|ga|cf|ru|cn)$' 
                     OR user_login REGEXP '^[0-9]{10,}$'
                     OR user_email REGEXP '[0-9]{10,}@')
            ");
            
            // Get total blocked IPs
            $blocked_ips = $wpdb->get_var("
                SELECT COUNT(DISTINCT option_name) 
                FROM {$wpdb->options} 
                WHERE option_name LIKE 'apchambers_blocked_ip_%'
            ");
            
            // Get rate limited IPs
            $rate_limited = $wpdb->get_var("
                SELECT COUNT(DISTINCT option_name) 
                FROM {$wpdb->options} 
                WHERE option_name LIKE 'apchambers_rate_limit_%'
            ");
            ?>
            
            <div class="stat-box">
                <div class="stat-number"><?php echo $recent_spam; ?></div>
                <div class="stat-label">Spam Users (30 days)</div>
            </div>
            
            <div class="stat-box">
                <div class="stat-number"><?php echo $blocked_ips; ?></div>
                <div class="stat-label">Blocked IPs</div>
            </div>
            
            <div class="stat-box">
                <div class="stat-number"><?php echo $rate_limited; ?></div>
                <div class="stat-label">Rate Limited IPs</div>
            </div>
        </div>
        
        <h2>Recent Spam Users (Last 30 Days)</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $spam_users = $wpdb->get_results("
                    SELECT ID, user_login, user_email, user_registered 
                    FROM {$wpdb->users} 
                    WHERE user_registered > DATE_SUB(NOW(), INTERVAL 30 DAY)
                    AND (user_email REGEXP '\.(tk|ml|ga|cf|ru|cn)$' 
                         OR user_login REGEXP '^[0-9]{10,}$'
                         OR user_email REGEXP '[0-9]{10,}@')
                    ORDER BY user_registered DESC
                    LIMIT 50
                ");
                
                foreach ($spam_users as $user) {
                    echo "<tr class='spam-user'>";
                    echo "<td>{$user->ID}</td>";
                    echo "<td>{$user->user_login}</td>";
                    echo "<td>{$user->user_email}</td>";
                    echo "<td>{$user->user_registered}</td>";
                    echo "<td><a href='#' onclick='deleteUser({$user->ID})' style='color: red;'>Delete</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        
        <h2>Blocked IP Addresses</h2>
        <table>
            <thead>
                <tr>
                    <th>IP Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $blocked_ips_data = $wpdb->get_results("
                    SELECT option_name, option_value 
                    FROM {$wpdb->options} 
                    WHERE option_name LIKE 'apchambers_blocked_ip_%'
                    OR option_name LIKE 'apchambers_rate_limit_%'
                ");
                
                foreach ($blocked_ips_data as $ip_data) {
                    $ip = str_replace(['apchambers_blocked_ip_', 'apchambers_rate_limit_'], '', $ip_data->option_name);
                    $status = strpos($ip_data->option_name, 'blocked') !== false ? 'Blocked' : 'Rate Limited';
                    
                    echo "<tr class='blocked-ip'>";
                    echo "<td>{$ip}</td>";
                    echo "<td>{$status}</td>";
                    echo "<td><a href='#' onclick='unblockIP(\"{$ip}\")' style='color: green;'>Unblock</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <script>
    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=delete_spam_user&user_id=' + userId + '&nonce=<?php echo wp_create_nonce('delete_spam_user'); ?>'
            }).then(() => location.reload());
        }
    }
    
    function unblockIP(ip) {
        if (confirm('Are you sure you want to unblock this IP?')) {
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=unblock_ip&ip=' + ip + '&nonce=<?php echo wp_create_nonce('unblock_ip'); ?>'
            }).then(() => location.reload());
        }
    }
    </script>
    
    <?php
    // Handle AJAX requests
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'delete_spam_user' && wp_verify_nonce($_POST['nonce'], 'delete_spam_user')) {
            $user_id = intval($_POST['user_id']);
            wp_delete_user($user_id);
            echo json_encode(['success' => true]);
            exit;
        }
        
        if ($_POST['action'] === 'unblock_ip' && wp_verify_nonce($_POST['nonce'], 'unblock_ip')) {
            $ip = sanitize_text_field($_POST['ip']);
            delete_transient('apchambers_blocked_ip_' . $ip);
            delete_transient('apchambers_rate_limit_' . $ip);
            echo json_encode(['success' => true]);
            exit;
        }
    }
    ?>
</body>
</html>
