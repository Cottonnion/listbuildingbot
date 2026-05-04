<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

use Dompdf\Dompdf;
require_once SQB_PD_DIR.'/inc/dompdf/autoload.inc.php';

$conversation_id = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : 0;

global $wpdb;

if(isset($_REQUEST['preview'])){
    $pdf_id = $_REQUEST['pdf-id'];
}else{
    $conversation_table = $wpdb->prefix . 'lbb_messages';
    $query = $wpdb->prepare(
        "SELECT GROUP_CONCAT(outcomes) AS outcomes FROM {$conversation_table} WHERE conversation_id = %d AND outcomes <> ''",
        $conversation_id
    );

    $result = $wpdb->get_var($query);

    $array = explode(',', $result);
    $counts = array_count_values($array);

    // Find the element with the maximum count
    $maxCount = max($counts);
    $mostFrequentElements = [];

    foreach ($counts as $element => $count) {
        if ($count === $maxCount) {
            $mostFrequentElements[] = $element;
        }
    }


    $outcome_id = implode(', ', $mostFrequentElements);
    $chatflow_id = isset($_REQUEST['chatflow_id']) ? $_REQUEST['chatflow_id'] : 0;
    $outcome_post_id = isset($_REQUEST['outcome_post_id']) ? $_REQUEST['outcome_post_id'] : 0;
    $outcome_maping = get_post_meta($outcome_post_id, 'pdfmap', true);


    $pdf_id = 0;
    if(!empty($outcome_maping)){
        foreach ($outcome_maping as $key => $map) {
            if($map['outcome_id'] == $outcome_id){
                $pdf_id = $map['pdf_id'];
                break;
            }
        }
    }
}
$pdf_table = $wpdb->prefix . 'lbb_pdfbuilder';
$query = $wpdb->prepare(
    "SELECT * FROM {$pdf_table} WHERE id = %d",
    $pdf_id
);

$pdfData = $wpdb->get_row($query);
if(empty($pdfData)){
    header("HTTP/1.0 404 Not Found");
    echo "404 - Invalid PDF";
    exit;
}

$pdf_title = $pdfData->name;

$pdfArray = maybe_unserialize($pdfData->content);
$pdfOptions = maybe_unserialize($pdfData->other_options);

$page_view = isset($pdfOptions['page_view']) ? $pdfOptions['page_view'] : 'portrait';
$pageHtml = '';

$pdf_header = "#d33838";
$pdf_footer_background_color = "#f9a3a3";
$pdf_logo_upload = "";
$pdf_global_font_val = 'sans-serif';
$pdf_footer_content = '%%DOMAIN%% ©%%YEAR%% All Rights Reserved';
$pdf_header_title = 'This is the header';

if(!empty($pdfArray)){
   
    foreach ($pdfArray as $index => $page) {
        $type = $page['type'];
        $data = $page['data'];

        $pageHtml .= '<div class="pdf-pageview-'.$page_view.' pdf-pageimage-'.$type.' pdf-page pdf-page-'.($index+1).'">';

        if($type == 'text'){
            $pageHtml .=  '<div class="sqb-pdf-page-content">'.stripcslashes($data).'</div>';
        }else if($type == 'image'){
             $pageHtml .= '<div class="inside-pdf-img-wrapper" style="background-image: url('.$data.');"><img src="" /></div>';
        }

        $pageHtml .= '</div>';
        //$pageHtml .= '<div class="page-break"></div>';
       
    }
    $pdf_data = $pageHtml;
    $lbb_pdf_header_footer = get_option('lbb_pdf_header_footer');

    $font_url = site_url().'/wp-content/plugins/listbuildingbot/public/';
    $gdpr_font_url = site_url().'/wp-content/plugins/gdprlibrary/assets/';
    $pdf_global_font = isset($lbb_pdf_header_footer['lbb_pdf_global_font_val']) ? $lbb_pdf_header_footer['lbb_pdf_global_font_val'] : '';
    $pdf_global_font = (!empty($pdf_global_font) && $pdf_global_font != '')? $pdf_global_font : 'sans-serif';

    $pdf_mgenplus_bolf = ($pdf_global_font == 'mgenplus') ? '* #sqb_quiz_builder .quiz_outer_fe strong, * b, strong, strong *, b *, * h1,* h2, * h3, * h4, * h5, * h6{ font-weight : 600 !important;}' : '';

   $pdf_viatname_bolf = ($pdf_global_font == 'BeVietnamPro, sans-serif') ? '* #sqb_quiz_builder .quiz_outer_fe strong, * b, * h1,* h2, * h3, * h4, * h5, * h6{ font-weight : 400 !important;}' : '';


    //$pdf_header = isset($lbb_pdf_header_footer['lbb_pdf_header']) ? $lbb_pdf_header_footer['lbb_pdf_header']: $pdf_header;
    //$pdf_footer = isset($lbb_pdf_header_footer['lbb_pdf_footer']) ? $lbb_pdf_header_footer['lbb_pdf_footer'] : $pdf_footer;
    $paged_based_css = '';
    $pdf_header_background_color = isset($lbb_pdf_header_footer['lbb_pdf_header']) ? $lbb_pdf_header_footer['lbb_pdf_header']: $pdf_header_background_color;
    $pdf_footer_background_color = isset($lbb_pdf_header_footer['lbb_pdf_footer']) ? $lbb_pdf_header_footer['lbb_pdf_footer']: $pdf_footer_background_color;
    $add_pdf_icon = isset($lbb_pdf_header_footer['lbb_pdf_logo_upload']) ? $lbb_pdf_header_footer['lbb_pdf_logo_upload'] : $add_pdf_icon;
    $pdf_header_title = isset($lbb_pdf_header_footer['lbb_pdf_header_title']) ? $lbb_pdf_header_footer['lbb_pdf_header_title']: '';
    $pdf_footer_copyright_content = isset($lbb_pdf_header_footer['lbb_pdf_footer_content']) ? $lbb_pdf_header_footer['lbb_pdf_footer_content']: '%%DOMAIN%% ©%%YEAR%% All Rights Reserved';



    $pdf_header = '<table id="table-header" style="width: 100%;margin: 0;padding: 0;text-align: left;background-color:'.$pdf_header_background_color.';border: none;border-collapse: collapse;"><tr style="vertical-align: middle;"><th style="width:30%;padding:10px 20px;text-align:left; "><img style="max-width: 100px;max-height:60px;width:auto;height:auto;" src="'.$add_pdf_icon.'"></th><th style="width:70%;padding:10px 20px;color:#171717;"><div style="vertical-align: middle;font-size: 19px;line-height: 1;margin: 0;padding: 0;text-align:right;width:100%;">'.$pdf_header_title.'</div></th></tr></table>';

    if(empty($pdf_header_title) && empty($add_pdf_icon)){
        $pdf_header = '';
    }

    if(!empty($pdf_footer_copyright_content)){
        $pdf_footer = '<table id="table-footer" style="width: 100%;margin: 0;padding: 0;text-align: center;background-color:'.$pdf_footer_background_color.';border: none;border-collapse: collapse;"><tr><th style="width:100%;padding:10px 20px;">'.$pdf_footer_copyright_content.'</th></tr></table>';
    }



    

    $pdf_data_final =  $pdf_header.$pdf_footer.'<div class="sqb_pdf_page_based sqb_quiz_container_outer  template_num_template1 inpage_popup_sqb  sqb_mobile_view_layout_active " id="sqbquizouter_333"> <div class="sqb_counter_outer"  ><div class="pdf-content-part">'.$pdf_data.'</div></div></div>';


    if ($page_view == 'landscape') {
        $a4_size = '@page { margin-left: 0; margin-right: 0; }';
    }else{
         $a4_size = '@page { margin-left: 0; margin-right: 0; margin-top: 0; size: A4; }';
    }

    $conditional_css = '';

    if(!empty($pdf_footer_copyright_content)){
        $conditional_css.= '@page {margin-bottom: 1.5cm;}
        #table-footer{ bottom: -1.5cm;}
        .inside-pdf-img-wrapper { top: 0; }';
    }
    
    if(!empty($pdf_header_title) || !empty($add_pdf_icon)){
        $conditional_css.= '@page { margin-top: 2.5cm; }
        #table-header{ top: -2.5cm;}
        .inside-pdf-img-wrapper { top: -2.5cm; }';
    }

    

    $paged_based_css = $a4_size.'
        .sqb_pdf_page_based .pdf-page { page-break-after: always; }
        .inside-pdf-img-wrapper { page-break-after: always;  position:absolute; left:0; right:0; bottom:0; top:0; width: 595pt; height:842pt; margin: 0!important; background-size: cover; background-position: center;}
        .sqb-pdf-page-content{ padding: 20px 0;}
        .pdf-pageview-landscape .inside-pdf-img-wrapper{width: 842pt; height:595pt;}
        
        .sqb-pdf-page-content{padding-bottom: 0; }'.$conditional_css;




   $demo = '
    <html>
        <head>  
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style> 

            @font-face {
                font-family: "BeVietnamPro";
                src: url("'.$font_url.'fonts/BeVietnamPro.ttf");
            }

            @font-face {
                font-family: "mgenplus";
                src: url("'.$gdpr_font_url.'fonts/mgenplus-1c-medium.ttf");
                font-weight: 400;
            }

             @font-face {
                font-family: "mgenplus";
                src: url("'.$gdpr_font_url.'fonts/mgenplus-1c-bold.ttf");
                font-weight: 600;
            }

                .page-break {
                            page-break-before: always;
                        }
                


                .pdf-content-part{ padding: 0 20px; margin: 10px!important;}
                .pmaster{margin-top: 1em; margin-bottom: 1em;}
                .nopaddingdiv{margin-top: 0; margin-bottom: 0;}


                #table-header {position: fixed; top: -2.5cm; left: 0cm; right: 0cm; height: 2cm; color: white; text-align: center; line-height: normal; }
                .result_temp_outer .sqbHideTemplateImageOuter .sqbHideTemplateImage, .result_temp_outer .sqbHideOutcomeDescriptionOuter .sqbHideOutcomeDescription,.result_temp_outer .sqbShowTemplateImageOuter .sqbShowTemplateImage {display:none;}
                #table-footer {position: fixed; bottom: -2.5cm; left: 0cm; right: 0cm; height: 1.5cm; color: white; text-align: center; line-height: normal; }
                
                *{ font-family: '.$pdf_global_font.'!important;}
                '.$pdf_viatname_bolf.'
                '.$pdf_mgenplus_bolf.'
                table.mce-item-table tr{ display:table-row;}
                table.mce-item-table td{ width: auto;}
                table.mce-item-table {width: 100%; background-color: #ffffff; border-collapse: collapse; border-width: 2px; border-color: #000; border-style: solid; color: #000000; }

                table.mce-item-table td, table.mce-item-table th {border-width: 2px; border-color: #000; border-style: solid; padding: 3px; }
                .pdf-content-part img{ margin-left: 0 !important; margin-right: 0 !important;}
                .canvas_image {display: block !important;text-align: center !important;}
                .canvas_image img { max-width: 100%;  height: auto;}
                

                  
                '.$paged_based_css.'
            </style>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body>
            '.$pdf_data_final.'
            <style> body{margin: 0;} </style>
        </body>
    </html>';


}else{
    $pdf_data_final = '';
}

$head = '';
$body = $demo;
$footer = '';

$dompdf = new Dompdf(array('enable_remote' => true));
$html = $head.$body.$footer;        

$placeholders = [
    '%%DOMAIN%%' => site_url(),
    '%%YEAR%%' => date('Y'),
    '%%HEADERTITLE%%' => $pdf_title
];

$html = preg_replace_callback('/%%[^%]+%%/i', function ($match) use ($placeholders) {
    $placeholder = $match[0];
    return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
}, $html);

$filename = 'lbb-outcome.pdf';
$dompdf->loadHtml($html); 
// (Optional) Setup the paper size and orientation
if ($page_view == 'landscape') {
    $dompdf->setPaper('A4', 'landscape');
}else{
    $dompdf->setPaper('A4', 'portrait');
}
// Render the HTML as PDF
$dompdf->render();
$dompdf->stream($filename);
exit;
?>