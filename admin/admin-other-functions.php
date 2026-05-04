<?php
add_action('wp_ajax_lbb_clone_question', 'lbb_clone_question');
function lbb_clone_question(){
  $post_id = $_REQUEST['question_id'];
  $post = get_post($post_id);
  if (!isset($post) || $post == null) {
    return;
  }
  $new_post = array(
    'post_title' => $post->post_title . ' (Clone)',
    'post_content' => $post->post_content,
    'post_status' => $post->post_status,
    'post_type' => $post->post_type,
  );
  $new_post_id = wp_insert_post($new_post);
  if ($new_post_id) {
    $post_meta = get_post_custom($post_id);

    foreach ($post_meta as $key => $value) {
      if (strpos($key, '_') !== 0) {
        $get_value = get_post_meta($post_id, $key, true);

        if ($get_value !== '') {
          update_post_meta($new_post_id, $key, $get_value);
        }
      }
    }

    $response = array(
      'message' => '',
      'post'    => LBB_Questions::getQuestion($new_post_id),
      'html'    => getLBBQuestionHTML($new_post_id)
    );
    wp_send_json_success($response);
  }else{
    $response = array('message' => 'Error inserting post.');
    wp_send_json_error($response);
  }
}