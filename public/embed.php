<?php

$lbb_chatflow_id = isset($_GET['id']) ? $_GET['id'] : 0;

echo "<link rel='stylesheet' id='chat-fe-css' href='https://newdemo.membershipsitechallenge.com/wp-content/plugins/listbuildingbot/admin/css/chat-fe.css?ver=1.0.0' type='text/css' media='all' />";

echo do_shortcode('[ListBuildingBot id="'.$lbb_chatflow_id.'"]');



print_embed_scripts();

wp_print_footer_scripts();

?>