<?php
/**
 * Task related utilities and manipulations
 * (code wiil be modevd here from customizer.php and extras.php)
 **/

/** Tasks custom status **/
add_action('init', 'tst_custom_task_status');
function tst_custom_task_status(){
    /**
     * Предполагаем, что:
     * draft - черновик задачи,
     * publish - задача открыта,
     * in_work - задача в работе,
     * closed - закрыта.
     */

    register_post_status('in_work', array(
        'label'                     => __('In work', 'tst'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('In work <span class="count">(%s)</span>', 'In work <span class="count">(%s)</span>'),
    ));
    register_post_status('closed', array(
        'label'                     => __('Closed', 'tst'),
        'public'                    => true,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Closed <span class="count">(%s)</span>', 'Closed <span class="count">(%s)</span>'),
    ));
	register_post_status('archived', array(
        'label'                     => __('Archived', 'tst'),
        'public'                    => true,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Closed <span class="count">(%s)</span>', 'Closed <span class="count">(%s)</span>'),
    ));
}

/** Prevent tasks authors to be overriden **/
add_filter('wp_insert_post_data', 'tst_preserve_task_author', 2,2);
function tst_preserve_task_author($data, $postarr) {
		
	if(!empty($postarr['ID']) && $data['post_type'] == 'tasks') {
		$post_id = (int)$postarr['ID'];
		$post_before = get_post($post_id);
		$data['post_author'] = $post_before->post_author;
	}
	
	return $data;
}


/** AJAX on task edits **/
function ajax_add_edit_task(){

    $_POST['id'] = (int)$_POST['id'] > 0 ? (int)$_POST['id'] : 0;
    $itv_log = ItvLog::instance();
    
    $params = array(
        'post_type' => 'tasks',
        'post_title' => htmlentities(trim($_POST['title']), ENT_COMPAT, 'UTF-8'),
        'post_content' => htmlentities(trim($_POST['descr']), ENT_COMPAT, 'UTF-8'),
        'tags_input' => $_POST['tags'],
    );
	
	$is_new_task = false;
    if($_POST['id']) { // Updating a task
        $params['ID'] = $_POST['id'];

        if(isset($_POST['status']) && $_POST['status'] == 'trash') {

            wp_trash_post($_POST['id']);
            $itv_log->log_task_action($_POST['id'], ItvLog::$ACTION_TASK_DELETE, get_current_user_id());            

            wp_die(json_encode(array(
                'status' => 'deleted',
                'message' => __('The task was successfully deleted.', 'tst'),
            )));
        } else
            $params['post_status'] = $_POST['status'];

    }
    // New task
    else {
        $is_new_task = true;
        $params['post_status'] = isset($_POST['status']) ? $_POST['status'] : 'draft';        
    }

    $_POST['id'] = wp_insert_post($params);
	
    if($_POST['id']) {
        $old_is_tst_consult_needed = get_field('is_tst_consult_needed', $_POST['id']);
        $new_is_tst_consult_needed = (int)$_POST['is_tst_consult_needed'] ? true : false;
        
		//update_field doesn't work for some reason - use native functions
		update_post_meta((int)$_POST['id'], 'about-author-org', htmlentities(trim(isset($_POST['about_author_org']) ? $_POST['about_author_org'] : '')));
        update_post_meta((int)$_POST['id'], 'reward', (int)$_POST['reward']);
		update_post_meta((int)$_POST['id'], 'is_tst_consult_needed', $new_is_tst_consult_needed);
		
		
        if($is_new_task) {
        	$itv_log->log_task_action($_POST['id'], ItvLog::$ACTION_TASK_CREATE, get_current_user_id());
            tst_send_admin_notif_new_task($_POST['id']);
        }
        else {
        	$itv_log->log_task_action($_POST['id'], ItvLog::$ACTION_TASK_EDIT, get_current_user_id());
        }
        
        if($new_is_tst_consult_needed) {
            if($is_new_task || !$old_is_tst_consult_needed) {
                update_field('is_tst_consult_done', false, $_POST['id']);
                tst_send_admin_notif_consult_needed($_POST['id']);
                tst_send_user_notif_consult_needed($_POST['id']);
            }
        }
				
        if($params['post_status'] == 'draft') {
            wp_die(json_encode(array(
                'status' => 'saved',
//            'message' =>  ?
//                    __('The task was successfully saved.', 'tst') :
//                    __('The task was successfully created.', 'tst'),
                'id' => $_POST['id'],
            )));
        } else
            wp_die(json_encode(array(
                'status' => 'ok',
//            'message' =>  ?
//                    __('The task was successfully saved.', 'tst') :
//                    __('The task was successfully created.', 'tst'),
                'id' => $_POST['id']
            )));

    } else {

        wp_die(json_encode(array(
            'status' => 'fail',
            'message' => empty($params['ID']) ?
                __('<strong>Error:</strong> something occured due to the task addition.', 'tst') :
                __('<strong>Error:</strong> something occured due to task edition.', 'tst'),
        )));
    }
}
add_action('wp_ajax_add-edit-task', 'ajax_add_edit_task');
add_action('wp_ajax_nopriv_add-edit-task', 'ajax_add_edit_task');