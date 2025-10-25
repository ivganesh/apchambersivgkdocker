<?php
/**
 * Plugin Name: AP Chambers Anti-Spam Registration
 * Description: Prevents spam user registrations and adds security measures
 * Version: 1.0
 * Author: AP Chambers
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Disable WordPress default user registration
 */
add_filter('option_users_can_register', '__return_false');

/**
 * Block access to registration page
 */
add_action('wp_loaded', function() {
    if (is_admin() && isset($_GET['action']) && $_GET['action'] === 'register') {
        wp_die('Registration is disabled for security reasons.');
    }
});

/**
 * Block REST API user registration
 */
add_filter('rest_pre_insert_user', function($user, $request) {
    // Block if not authenticated or not admin
    if (!is_user_logged_in() || !current_user_can('create_users')) {
        return new WP_Error('rest_cannot_create_user', 'User registration via REST API is disabled.', array('status' => 403));
    }
    return $user;
}, 10, 2);

/**
 * Add CAPTCHA to custom registration forms
 */
add_action('wp_footer', function() {
    if (is_page() && has_shortcode(get_post()->post_content, 'UserRegistrationPage')) {
        ?>
        <script>
        // Add simple math CAPTCHA
        function generateMathCaptcha() {
            const num1 = Math.floor(Math.random() * 10) + 1;
            const num2 = Math.floor(Math.random() * 10) + 1;
            const answer = num1 + num2;
            document.getElementById('captcha-question').textContent = `${num1} + ${num2} = ?`;
            document.getElementById('captcha-answer').value = answer;
            return answer;
        }
        
        // Generate CAPTCHA on page load
        document.addEventListener('DOMContentLoaded', function() {
            generateMathCaptcha();
        });
        </script>
        <?php
    }
});

/**
 * Log suspicious registration attempts
 */
add_action('init', function() {
    if (isset($_POST['REGISTER']) || isset($_POST['wp-submit'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Check for suspicious patterns
        $is_suspicious = false;
        $reasons = [];
        
        if (isset($_POST['user_email'])) {
            $email = $_POST['user_email'];
            if (preg_match('/.*@.*\.(tk|ml|ga|cf|ru|cn)$/i', $email)) {
                $is_suspicious = true;
                $reasons[] = 'suspicious_email_domain';
            }
        }
        
        // Check for common spam user agents
        $spam_user_agents = ['bot', 'crawler', 'spider', 'scraper'];
        foreach ($spam_user_agents as $spam_ua) {
            if (stripos($user_agent, $spam_ua) !== false) {
                $is_suspicious = true;
                $reasons[] = 'suspicious_user_agent';
                break;
            }
        }
        
        if ($is_suspicious) {
            error_log('AP Chambers: Suspicious registration attempt from IP: ' . $ip . 
                     ' Reasons: ' . implode(', ', $reasons) . 
                     ' User Agent: ' . $user_agent);
        }
    }
});

/**
 * Add honeypot field to registration forms
 */
add_action('register_form', function() {
    echo '<input type="text" name="website" style="display:none !important" tabindex="-1" autocomplete="off">';
});

add_filter('registration_errors', function($errors, $sanitized_user_login, $user_email) {
    // Check honeypot
    if (!empty($_POST['website'])) {
        $errors->add('honeypot_error', 'Spam detected.');
    }
    
    // Additional email validation
    if (!is_email($user_email)) {
        $errors->add('invalid_email', 'Invalid email address.');
    }
    
    return $errors;
}, 10, 3);

/**
 * Rate limiting for registration attempts
 */
add_action('wp_login_failed', function($username) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $rate_limit_key = 'apchambers_registration_attempts_' . $ip;
    $attempts = get_transient($rate_limit_key);
    
    if ($attempts && $attempts >= 3) {
        // Block for 1 hour after 3 failed attempts
        set_transient('apchambers_blocked_ip_' . $ip, true, 3600);
        error_log('AP Chambers: IP blocked for excessive registration attempts: ' . $ip);
    } else {
        $attempts = $attempts ? $attempts + 1 : 1;
        set_transient($rate_limit_key, $attempts, 3600);
    }
});

/**
 * Block IPs that are temporarily blocked
 */
add_action('init', function() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (get_transient('apchambers_blocked_ip_' . $ip)) {
        if (isset($_POST['wp-submit']) || isset($_POST['REGISTER'])) {
            wp_die('Too many registration attempts. Please try again later.');
        }
    }
});
