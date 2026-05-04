<?php 

global $wpdb;
$tableName = $wpdb->prefix . 'lbb_pdfbuilder';
$sql = "SELECT * FROM " . $tableName ." ORDER BY date desc";
$results = $wpdb->get_results( $sql);
?>

<?php get_lbb_header(); ?>
<div class="lbb-header-part-content">
	<h1 class="wp-heading-inline">PDF Builder</h1>
</div>
<section class="lbb-outer-section">
	<div class="lbb-container">
		<div class="lbb-content">
			<div class="lbb-datatable">
				<div class="lbb-datatable-btn">
					<a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=lbb_create_pdf_content_page" class="create-chatflow lbb-model-open-btn lbb-btn lbb-btn-primary">Create a New PDF</a>
				</div>
				<table class="lbb-table lbb-table-style" >
					<thead>
	              	<tr>
	                	<th class="">Name</th>
	                	<th class="lbb-w-150p">Date</th>
	                	<th class="lbb-w-200p">Action</th>
	              	</tr>
		            </thead>
		            <tbody>
		            	<?php 
		            		foreach ($results as $key => $row) {
								$id = $row->id;
								$short_date = $row->date;
								if($short_date == '0000-00-00 00:00:00'){
									$shortcode_date = date('d-m-Y');
								}else{
									$shortcode_date = date('d-m-Y', strtotime($short_date));
								}
								?>
								<tr class="cpb_member_manage_page_row_id_<?php echo $id ?> odd" role="row">
									<td align="left" class="lbb-strong-link-type">
										<a class="" href="<?php echo admin_url('admin.php?page=lbb_create_pdf_content_page&id='.$id); ?>">
											<?php echo $row->name ?>
										</a>
									</td>
									<td align="center" class="sorting_1"><?php echo $shortcode_date; ?></td>
									<td align="center"><div style="display:none;"><?php echo $id ?></div>

										<div class="lbb-action-btn-wrapper">
							    			<a class="lbb-icon-transparent-btn lbb-edit-btn" href="<?php echo admin_url('admin.php?page=lbb_create_pdf_content_page&id='.$id); ?>">
								    			<span class="dashicons dashicons-edit"></span> Edit
								    		</a>
								    		
								    		<a class="lbb-icon-transparent-btn  delete_pdf_content_by_id  lbb-delete-btn" data-id="<?php echo $id ?>" href="javascript:void(0)">
								    			<span class="dashicons dashicons-trash"></span> Delete
								    		</a>

							    		</div>
									</td>
								</tr>
						<?php } ?>
		            </tbody>
		        </table>
			</div>
		</div>
	</div>
</section>
<?php get_lbb_footer(); ?>