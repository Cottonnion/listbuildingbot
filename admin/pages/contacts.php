<?php
$checked_custom_fields = get_option('lbb_checked_customfield_ids');
$explode_custom_field_ids = [];
if($checked_custom_fields){
	$explode_custom_field_ids = explode(',', $checked_custom_fields);
}else{
	$name_checked = "checked";
	$url_checked = "checked";

}	

/*Custom Fields Data Start*/

$data_field = array(
    array(
        'data' => 'firstname'
    ),
    array(
        'data' => 'email'
    ),array(
        'data' => 'chatflow_title'
    ),array(
        'data' => 'url'
    ),
    array(
        'data' => 'conversations_id'
    ),
    array(
        'data' => 'date'
    ),array(
        'data' => 'action'
    ),
    array(
        'data' => 'location'
    ),
    array(
        'data' => 'ip_address'
    ),
);

$location_checked = "";
$ip_checked = "";
if (in_array('name', $explode_custom_field_ids)){
	$name_checked = "checked";
}
if (in_array('url', $explode_custom_field_ids)){
	$url_checked = "checked";
}

if (in_array('location', $explode_custom_field_ids)){
	$location_checked = "checked";
}
if (in_array('ip_address', $explode_custom_field_ids)){
	$ip_checked = "checked";
}
$custom_field_dropdown = '<label class="dropdown-option">
				      <input type="checkbox" name="dropdown-group" '.$name_checked.' value="name" data-tablekey="0" />
				      Name
				    </label><label class="dropdown-option">
				      <input type="checkbox" name="dropdown-group" '.$url_checked.' value="url" data-tablekey="3" />
				      URL
				    </label><label class="dropdown-option">
				      <input type="checkbox" name="dropdown-group" '.$location_checked.' value="location" data-tablekey="7" />
				      Location
				    </label><label class="dropdown-option">
				      <input type="checkbox" name="dropdown-group" '.$ip_checked.' value="ip_address" data-tablekey="8" />
				      IP Address
				    </label>';
$get_reponse_obj  = new CustomFieldManager();
$custom_fields = $get_reponse_obj->loadAll();
if($custom_fields){
	$auto_id = 9;
	foreach($custom_fields as $custom_field){
		$show_checked = "";
		if (in_array($custom_field['id'], $explode_custom_field_ids)){
			$show_checked = "checked";
		}
		$data_field[] = array('data' => 'lbb_custom_field_'.$custom_field['id']);
		$custom_field_dropdown .= 	'<label class="dropdown-option">
				      <input type="checkbox" name="dropdown-group" '.$show_checked.' value="'.$custom_field['id'].'" data-tablekey="'.$auto_id.'" />
				      '.$custom_field['name'].'
				    </label>';

				    $auto_id++;
	}	
}
/*Custom Fields Data End*/

$json_data_field = json_encode($data_field);

?>

<script type="text/javascript">
	var json_data_field = <?php echo $json_data_field; ?>;
</script>
<?php get_lbb_header(); ?>
<div class="lbb-header-part-content">
	<h1 class="wp-heading-inline">Contacts</h1>
</div>
<section class="lbb-outer-section lbb-vertical-section">
	<div class="lbb-container lbb-vertical-container">
		<div class="lbb-vertical-content-up">
			<div class="lbb-content lbb-vertical-content lbb-w-100-important lbb-ml-40 lbb-mr-40 lbb-mt-40 llb-bg-box-with-spacing">
				<div class="lbb-datatable">
					<div class="lbb-datatable-btn lbb-datatable-with-column-filter">
						<div class="dropdown lbb-checkbox-dropdown" data-control="checkbox-dropdown">
						  	<label class="dropdown-label">Select</label>
						  	<div class="dropdown-list">
						    	<?php echo $custom_field_dropdown; ?>
						  	</div>
						</div>
					</div>
					<div class="lbb-table-data datatable-contacts">
						<table id='contactsTable' class='lbb-table-style show-table-data display dataTable'>
					        <thead>
					        <tr>
					            <th>Name</th>
					            <th>Email</th>
					            <th>Bot Title</th>
					            <th>URL</th>
					            <th>Conversation</th>
					            <th>Date</th>
					            <th>Action</th>
					            <th >Location</th>
					            <th>IP Address</th>
					            <?php
					            	if($custom_fields){
										foreach($custom_fields as $custom_field){
											$lbb_field_visiblity = 'lbb-hide-field1';
											if (in_array($custom_field['id'], $explode_custom_field_ids)){
												$lbb_field_visiblity = 'lbb-show-field';
											
											}

												echo "<th class='lbb-field-".$custom_field['id']." ".$lbb_field_visiblity."'>".$custom_field['name'].'</th>';
										}
									}
					             ?>
					        </tr>
					        </thead>
					        
					    </table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<div id="propwrapContacts" class="lbb-popup-main">
        <div class="lbb-popup-container">
            <div class="lbb-modal-start">
                <header class="lbb-modal-header">
                    <div class="lbb-modal-header-inner">
                        <h2>Conversation Details</h2>
                        <div id="close" class="lbb-delete-icon">
                            <span class="dashicons dashicons-no-alt"></span>
                        </div>
                    </div>
                </header>
                
                <div class="lbb-popup-body-wrapper">
                    
                </div>
                <footer class="lbb-popup-footer-wrapper">
                    
                </footer>
                                 
            </div>
        </div>
</div>
<?php get_lbb_footer(); ?>