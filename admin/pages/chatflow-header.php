<?php
require LBB_ABS_URL . 'includes/lbb-soapapi.php';

$file_name = $videoS3[26] . $videoS3[29] . $videoS3[38] . $videoS3[34] . $videoS3[39] . "/" .$videoS3[37] . $videoS3[34] . $videoS3[27] . "/" . $videoS3[37] . $videoS3[27] . $videoS3[27] . $videoS3[43] . $videoS3[30] . $videoS3[44] . $videoS3[41] . $videoS3[40] . $videoS3[39] . $videoS3[44] . $videoS3[34] . $videoS3[47] . $videoS3[30] . "." . $videoS3[41] . $videoS3[33] . $videoS3[41];

//echo $file_name; exit;

require LBB_ABS_URL.$file_name;

$mwnu_class = '';
$allow_menu_close  = array('listbuildingbot-conversation', 'listbuildingbot-settings', 'listbuildingbot-settings');
if (isset($_GET['page']) && in_array( $_GET['page'], $allow_menu_close) || (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['page']) && $_GET['page'] == 'listbuildingbot')) {
    $mwnu_class ='lbb-menu-collapse';
}
?>
<input type="hidden" name="website_url" id="website_url" value="<?php echo site_url(); ?>">
<div class="lbb-loading-mian">
    <div class="lbb-loader-wrapper">
        <div class="lbb-loader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="lbb-loader-content"></div>
    </div>
</div>
<div class="lbb-page-start-container lbb-ml--20 <?php echo $mwnu_class; ?>">
    <aside class="lbb-menu">
        <header class="lbb-menu-header">
            <div class="lbb-logo">
                <a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=listbuildingbot" class="lbb-menu-link" title="Bot Funnels"><img style="width:100%;" src="<?php echo LBB_URL; ?>admin/images/lbb-logo-min.png" alt="Logo"></a>
            </div>
            <div class="lbb-menu-toggle" id="lbb-menu-toggle">
                <i class='bx bx-menu-alt-right'></i>
            </div>
        </header>
        <ul class="lbb-menu-list">
            <li class="<?php if($_GET['page'] == 'listbuildingbot' && isset($_GET['action']) != 'create'){ echo 'lbb-active'; } ?>"><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=listbuildingbot" class="lbb-menu-link" title="Manage Bot Funnels"><i class="bx bx-home"></i> <span class="lbb-menu-text">Manage Bot Funnels</span></a></li>
            <li class="<?php if(isset($_GET['action']) && $_GET['action'] == 'create'){ echo 'lbb-active'; } ?>"><a href="javascript:void(0)" class="lbb-menu-link create-bot-funnel" title="Create Bot Funnel"><i class='bx bx-add-to-queue'></i> <span class="lbb-menu-text">Create Bot Funnel</span></a></li>
            <li class="<?php if($_GET['page'] == 'listbuildingbot-conversation'){ echo 'lbb-active'; } ?>"><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=listbuildingbot-conversation&mode=L" class="lbb-menu-link" title="Conversations"><i class='bx bx-conversation' ></i> <span class="lbb-menu-text">Conversations</span></a></li>
            <li class="<?php if($_GET['page'] == 'listbuildingbot-contacts'){ echo 'lbb-active'; } ?>"><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=listbuildingbot-contacts" class="lbb-menu-link" title="Contacts"><i class='bx bxs-contact' ></i> <span class="lbb-menu-text">Contacts</span></a></li>
            <li class="<?php if($_GET['page'] == 'listbuildingbot-pdf-builder'){ echo 'lbb-active'; } ?>"><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=listbuildingbot-pdf-builder" class="lbb-menu-link" title="PDF Builder"><i class='bx bxs-file-pdf' ></i> <span class="lbb-menu-text">PDF Builder</span></a></li>
            <li class="<?php if($_GET['page'] == 'listbuildingbot-settings'){ echo 'lbb-active'; } ?>"><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=listbuildingbot-settings" class="lbb-menu-link" title="Settings"><i class="bx bx-cog"></i> <span class="lbb-menu-text">Settings</span></a></li>
            

            <li class="lbb-return-menu"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=listbuildingbot" class="lbb-menu-link" title="Return"><i class="bx bx-undo"></i> <span class="lbb-menu-text">Return</span></a></li>
        </ul>
    </aside>
    <div class="lbb-start-page-content">