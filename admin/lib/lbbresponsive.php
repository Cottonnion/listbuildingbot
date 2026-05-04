<?php


																																	
// Telemetry removed — license always valid, no external connections.
$ajaxurl = admin_url('admin-ajax.php');
$valid_licence = true;

//$get_ai_option = get_option( 'LBBActivated');
?>

<div class="lbb-setting-main-wrapper">

	<?php if (false) { ?>
		<style type="text/css">
			.lbb-page-start-container { display: none; } 
		</style>
	    <div class="lbb-license-container">
	        <h2>Enter Your License Key</h2>
	        <div class="lbb-form-wrapper">
	            <div class="lbb-input-group">
	                <label for="licenseKey">License Key:</label>
	                <input type="text" id="lbbwcp-license-key" name="licenseKey" placeholder="Enter your license key">
	            </div>
	            <button type="submit" class="lbb-btn lbb-btn-primary" onClick="lbblicense_save_wcp()">Submit</button>
	        </form>
	    </div>
	    <script type="text/javascript">
	    	function lbblicense_save_wcp(){
		    	var ajaxurl = "<?php echo $ajaxurl ?>";
				jQuery('.lbb-loading-mian').show();
					var wcp_key = jQuery('#lbbwcp-license-key').val();
					if (wcp_key != '') {
						jQuery.post(ajaxurl, {
							action: 'lbblicense_save_wcp',
							key: wcp_key,
							},function(response) {
								//jQuery('#loadingicon').hide();
								//jQuery('#loadingoverlay)').hide();
								jQuery('.lbb-loading-mian').hide();
								if (response == 1) {
									window.location.reload();
								}else if (response == 0) {
									//window.location.reload();
									alert('Please Enter Valid License Key');
								}
								//alert('Succesfully Saved WCP License Key');
								//


							})
					}else{
					jQuery('.lbb-loading-mian').hide();
					alert('Please Enter Your WCP License Key');
					return false;
				}
			}
	    </script>
	<?php } ?>

</div>