<?php 

add_action('wp_ajax_cpb_save_pdf_content_data', 'cpb_save_pdf_content_data');
function cpb_save_pdf_content_data(){
	$data =  $_REQUEST['data'];
	$name =  $_REQUEST['name'];
	$other_options =  $_REQUEST['other_options'];
	$pdf_id =  $_REQUEST['pdf_id'];
	$current_date = date('Y-m-d H:m:s');

	$data_serialize = maybe_serialize($data);
	$other_options_serialize = maybe_serialize($other_options);
	
	$pdfcontent = new PDFContentManager();
	$args = array(
        'name' => $name,
        'content' => $data_serialize,
        'other_options' => $other_options_serialize,
        'date' => $current_date,
    );

    if($pdf_id){
      	$output['id'] = $pdfcontent->update($args, $pdf_id);
      	$output['table_action'] = 'update';
    }else{
     	$output['id'] = $pdfcontent->insert($args);
      	$output['table_action'] = 'create';
    }
	echo json_encode($output);die;
}

add_action('wp_ajax_cpbDeletePdfContentByIdAjax', 'cpbDeletePdfContentByIdAjax');
function cpbDeletePdfContentByIdAjax(){
	$output= array();
	if(isset($_POST)){ 	
		$id = $_POST['id'];
		$output['success'] = 'Deleted';
		$pdfcontent = new PDFContentManager();
		$pdfcontent->deleteById($id);
		$output['id'] = $id;
	}else{
		$output['error'] = 'something wrong!';
	}
	echo json_encode($output);
	die;
}	