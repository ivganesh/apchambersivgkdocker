<?php
/**
 * Spam User Cleanup Script for AP Chambers
 * Run this script to remove existing spam users
 */

// Include WordPress
require_once('wp-config.php');
require_once('wp-load.php');

global $wpdb;

echo "AP Chambers - Spam User Cleanup Script\n";
echo "=====================================\n\n";

// Define spam patterns
$spam_email_patterns = [
    '%.tk', '%.ml', '%.ga', '%.cf',  // Free domains
    '%.ru', '%.cn',                   // Common spam countries
    '%test%@',                        // Test emails
    '%@gmail.com', '%@yahoo.com', '%@hotmail.com'  // Common disposable
];

// Get spam users
$spam_users = [];
foreach ($spam_email_patterns as $pattern) {
    $users = $wpdb->get_results($wpdb->prepare("
        SELECT ID, user_login, user_email, user_registered 
        FROM {$wpdb->users} 
        WHERE user_email LIKE %s
    ", $pattern));
    
    $spam_users = array_merge($spam_users, $users);
}

// Remove duplicates
$spam_users = array_unique($spam_users, SORT_REGULAR);

// Also check for users with suspicious patterns in user_login
$suspicious_logins = $wpdb->get_results("
    SELECT ID, user_login, user_email, user_registered 
    FROM {$wpdb->users} 
    WHERE user_login REGEXP '^[0-9]{10,}$' 
    OR user_login LIKE 'test%'
    OR user_email REGEXP '[0-9]{10,}@'
");

$spam_users = array_merge($spam_users, $suspicious_logins);

echo "Found " . count($spam_users) . " potential spam users:\n\n";

foreach ($spam_users as $user) {
    echo sprintf("ID: %d, Login: %s, Email: %s, Registered: %s\n", 
        $user->ID, $user->user_login, $user->user_email, $user->user_registered);
}

echo "\nDo you want to delete these users? (y/N): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$confirm = trim($line);
fclose($handle);

if (strtolower($confirm) === 'y') {
    $deleted_count = 0;
    
    foreach ($spam_users as $user) {
        // Delete user (this will also delete user meta)
        if (wp_delete_user($user->ID)) {
            echo "Deleted user: {$user->user_login} ({$user->user_email})\n";
            $deleted_count++;
        } else {
            echo "Failed to delete user: {$user->user_login}\n";
        }
    }
    
    echo "\nDeleted {$deleted_count} spam users successfully.\n";
    
    // Clean up any orphaned user meta
    $wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE user_id NOT IN (SELECT ID FROM {$wpdb->users})");
    echo "Cleaned up orphaned user meta.\n";
    
} else {
    echo "Cleanup cancelled.\n";
}

echo "\nSpam cleanup completed.\n";
