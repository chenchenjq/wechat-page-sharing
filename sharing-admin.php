<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once 'wechat.php';

class Bosima_WeChat_Page_Sharing_Admin
{
    public static function init()
    {
        add_action('admin_menu', array('Bosima_WeChat_Page_Sharing_Admin', 'admin_menu'));
    }

    public static function admin_menu()
    {
        add_options_page('微信分享插件配置', '微信分享设置', 'manage_options', 'wechat-appid-config', array('Bosima_WeChat_Page_Sharing_Admin', 'display_page'));
    }

    public static function display_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        // variables for the field and option names
        $hidden_field_name = 'wechat_submit_hidden';
        $appid_field_name = 'wechat_appid';
        $appsecrect_field_name = 'wechat_appsecrect';

        $weChat = Bosima_WeChat::getInstance();
        $config = $weChat->getWeChatConfig();
        $wechat_appid = $config->appId;
        $wechat_appsecrect = $config->appSecrect;

        // See if the user has posted us some information
        // If they did, this hidden field will be set to 'Y'
        if (isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {
            // Read their posted value
            $wechat_appid = $_POST[$appid_field_name];
            $wechat_appsecrect = $_POST[$appsecrect_field_name];

            // Save the posted value in the database
            $weChat->updateStaticConfig($wechat_appid, $wechat_appsecrect);

            // Put an settings updated message on the screen
?>
            <div class="updated"><p><strong><?php _e('settings saved.', 'wechat-page-sharing'); ?></strong></p></div>
 <?php

        }
        echo '<div class="wrap">';
        echo '<h2>'.__('WeChat Page Sharing Plugin Settings', 'wechat-page-sharing').'</h2>'; ?>
            <form name="form1" method="post" action="">
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
            <p><?php _e('WeChat AppId:', 'wechat-page-sharing'); ?> 
            <input type="text" name="<?php echo $appid_field_name; ?>" value="<?php echo $wechat_appid; ?>" size="30">
            </p>
            <p><?php _e('WeChat AppSecrect:', 'wechat-page-sharing'); ?> 
            <input type="text" name="<?php echo $appsecrect_field_name; ?>" value="<?php echo $wechat_appsecrect; ?>" size="40">
            </p>
            <hr />

            <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
            </p>
            </form>
        </div>
<?php

    }
}