<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wickedcoolplugins.com
 * @since      1.0.0
 *
 * @package    Listbuildingbot
 * @subpackage Listbuildingbot/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Listbuildingbot
 * @subpackage Listbuildingbot/includes
 * @author     Veena Prashanth <veena@digitalaccesspass.com>
 */
class LBB_ChatFlow {

	public static function save($post_title,$post_id=0){
		$post_data = array(
			'ID' => $post_id,
	        'post_title' => $post_title,
	        'post_content' => '',
	        'post_status' => 'publish', 
	        'post_type' => 'lbb-chatflow',
	    );

	     // Insert the post and get the post ID
		if($post_id != 0){
	    	$post_id = wp_update_post($post_data);
		}else{
	    	$post_id = wp_insert_post($post_data);
		}

	    // Check if the post is successfully inserted
	    if (!is_wp_error($post_id)) {
	        return $post_id;
	    } else {
	        return 0;
	    }
	}

	public static function save_actions($post_title,$post_id=0){
		$post_data = array(
			'ID' => $post_id,
	        'post_title' => $post_title,
	        'post_content' => '',
	        'post_status' => 'publish', 
	        'post_type' => 'lbb-chatflow-action',
	    );

	     // Insert the post and get the post ID
		if($post_id != 0){
	    	$post_id = wp_update_post($post_data);
		}else{
	    	$post_id = wp_insert_post($post_data);
		}

		//update_post_meta( $id, 'action_ids', 1001,1002 );

	    // Check if the post is successfully inserted
	    if (!is_wp_error($post_id)) {
	        return $post_id;
	    } else {
	        return 0;
	    }
	}

	public static function getDrawflow($chatflow_id) {
		$drawflow_meta = get_post_meta($chatflow_id, '_questions_drawflow', true);
		$drawflow = [];
	
		if (!empty($drawflow_meta)) {
			$drawflow = json_decode($drawflow_meta, true);
		
			if(isset($drawflow['drawflow']['Home']['data'][0])){
				unset($drawflow['drawflow']['Home']['data'][0]);
			}
			foreach ($drawflow['drawflow']['Home']['data'] as $key => $element) {
				/*if ($key == 1) {
					continue;
				}*/
				$question_type = "";
				$question_id = $element['data']['question_id'];
				$question_type = get_post_meta($question_id, 'question_type', true);
				$drawflow['drawflow']['Home']['data'][$key]['html'] = getLBBQuestionHTML($question_id);
				$drawflow['drawflow']['Home']['data'][$key]['class'] .=  ' qt-'.$question_type.' node-question-'.$question_id; 
			}

			
			
		}else{
			$drawflow = array(
				'drawflow' => array(
					'Home' => array(
						'data' => array()
					)
				)
			);
		}
	
		return $drawflow;
	}	

}
