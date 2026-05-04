<?php 

define( 'LBB_PDF_ABS_PATH', plugin_dir_path( __FILE__ ) );
define( 'LBB_PDF_URL', plugin_dir_url( __FILE__ ) );

if (is_admin() && isset($_GET['page']) && ($_GET['page'] === 'listbuildingbot-pdf-builder' || $_GET['page'] === 'lbb_create_pdf_content_page')){
	add_action('admin_enqueue_scripts', 'pdf_builder_enqueue_styles');
	add_action('admin_enqueue_scripts', 'pdf_builder_enqueue_scripts');
}

function pdf_builder_enqueue_styles() {
	wp_enqueue_style( 'cpb-pdf-builder', LBB_PDF_URL . 'assets/pdf-builder.css', array(), '5.13.0', 'all' );
	wp_enqueue_style( 'cpb-font-awesome-latest-css','//use.fontawesome.com/releases/v5.3.1/css/all.css', false );
	wp_enqueue_style( 'cpb-font-awesome-css','//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false );
	wp_enqueue_style( 'cpb-jqueru-ui-css','//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css', false );
}

function pdf_builder_enqueue_scripts() {
	wp_enqueue_script('cpb-pdf-builder-js',  LBB_PDF_URL . 'assets/pdf-builder.js', array(), '5.13.0', true);
	wp_enqueue_script('cpb_tinymce_plugin-builder-js',  LBB_PDF_URL . 'assets/js/tinymce_plugin.min.js', array(), '5.13.0', true);
	wp_enqueue_script("cpb_tinymce_min_js", site_url('')."/wp-includes/js/tinymce/tinymce.min.js", array());
	
	wp_enqueue_script("cpb_jquery_ui_js", "https://code.jquery.com/ui/1.13.2/jquery-ui.js", array());
}

require_once LBB_PDF_ABS_PATH . 'pdf-functions.php';
require_once LBB_PDF_ABS_PATH . 'class-pdfbuilder.php';