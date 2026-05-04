<?php

function LBBDoInstall(){

    global $lbb_db_version;
   	
    

   	/*if( ! function_exists('get_plugin_data') ){
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $plugin_data = get_plugin_data(  LBB_ABS_URL.'listbuildingbot.php');
 
    $lbb_version = $plugin_data['Version'];*/

    lbb_upgrade();
}

function lbb_check_column_exist($tablename,$columnname){
	global $wpdb;

	$value = $wpdb->get_var("SELECT * 
    FROM INFORMATION_SCHEMA.COLUMNS 
	WHERE table_schema = '".DB_NAME."' 
    AND table_name = '".$tablename."'
    AND column_name = '".$columnname."'");

	return $value;
}

function lbb_upgrade()	
{
	global $wpdb;
	$plugin_data = get_plugin_data(  LBB_ABS_URL.'listbuildingbot.php');

	require LBB_ABS_URL.'admin/version.php';
	
	$old_version = get_site_option( 'lbb_db_version' );
	$current_version = $plugin_data['Version'];
	$charset_collate = $wpdb->get_charset_collate();

	$update_file_path = LBB_ABS_URL.'admin/updates/';
	if(!empty($versions)){
		foreach ($versions as $ver_key => $version) {
           
			if(version_compare($ver_key,$old_version, '>') && version_compare($ver_key,$current_version, '<=') ){
                
				if(file_exists($update_file_path.$version)){
					
					include($update_file_path.$version);
					if(!empty($updateSql)){
						foreach ($updateSql as $key => $sql) {
							try {
                                //echo $sql;
                                if(strpos($sql, 'CREATE TABLE IF NOT') !== false){
                                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                                    dbDelta($sql);
                                }else{
                                    $wpdb->query($sql);
                                }
							}
							catch (PDOException $e) {
								logToFile("install.php: table exists: ".$e->getMessage(), LOG_DEBUG_DAP);
							} catch (Exception $e) {
								logToFile("install.php: exception(), Error is".$e->getMessage(), LOG_DEBUG_DAP);
							}
						}
					}
				}
			}else{
				//echo "Old Version : $version<br />";
			}

		}
		update_option('lbb_db_version',$current_version);
	}
	
	//SQBDoInstall();
}