<?php
/*
	<a class="lbb-btn lbb-btn-primary" id="lbb-create-new-outcomes" href="javascript:void(0);">Create Outcome</a>
<div class="lbb-row lbb-align-items-stretch lbb-m-0 lbb-height-100vh-120">
    <div class="lbb-chatbot-preview-form-wrapper">
		<div class="lbb-accordion">

			<?php 

		        $outcomesmanager = new OutcomesManager();
  				$outcomedata = $outcomesmanager->loadAllByChatflow();
  				//echo '<pre>';print_r($outcomedata);
  				foreach($outcomedata as $outcome){
		   			echo  '<div class="lbb-accordion-item">';
	  					$chatflow_id = $outcome['chatflow_id'];
	  					$outcome_id = $outcome['id'];
	  						echo '
	  							<div class="lbb-accordion-header">
						            <h2 data-chatflow-id="'.$chatflow_id.'">'.get_the_title($chatflow_id).'</h2>
						            <div class="lbb-accordion-right-content">
						                <span class="lbb-arrow"></span>
						            </div>
					        	</div>
					        	<div class="lbb-accordion-content">
								    <div class="lbb-row">
								        <div class="lbb-col-12">
								            <div class="lbb-form-group lbb-outcome-main-container">';
					  							$names = $outcome['names'];
					  							$names = explode(',', $names);
							  					foreach($names as $name){
							  						echo '<div class="lbb-outcome-row-listing">
							  						<label for="lbb_admin_name">'.$name.'</label>
							  						<div class="lbb-ai-outcome-action">
								  						<div class="lbb-action-btn-wrapper">
												          <a data-id="'.$outcome_id.'" class="lbb-icon-btn lbb-outcomes-edit-btn">
												            <span class="dashicons dashicons-edit"></span>
												          </a>
												          <a data-id="'.$outcome_id.'" class="lbb-icon-btn lbb-delete-btn delete-outcomes" href="javascript:void(0)">
												            <span class="dashicons dashicons-trash"></span>
												          </a>
												        </div>
							  						</div>
							  						</div>';
							  					}
	  								echo '  </div>
				        				</div>
				    				</div>
								</div>
							</div>';
  				}
	        ?>

		        
				
				                
				                
				           
			
		</div>
	</div>
</div>

*/ ?>
<div class="lbb-box lbb-section-bg-box">
	<div class="lbb-datatable">
		<div class="lbb-datatable-btn">
			<a class="lbb-btn lbb-btn-primary" id="lbb-create-new-outcomes" href="javascript:void(0);">Create Outcome</a>
		</div>
		<div class="outcomes-data lbb-no-scoll-x">

		</div>
	</div>
</div>