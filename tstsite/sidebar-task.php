<?php
/**
 * @package Blank
 */

global $post;
$cur_user_id = get_current_user_id();
$is_curr_users_task = $cur_user_id == $post->post_author;
$candidates = tst_get_task_doers(false, false);
$doers = tst_get_task_doers(false, true);

/** Guest viewing */
if( !$cur_user_id ) {?>

    <div id="">
        <a href="<?php echo tst_get_login_url();?>" id="guest-help" class="btn btn-success btn-lg widefat" <?php echo $post->post_status != 'publish' ? 'disabled="disabled"' : '';?>>
            <?php _e('Offer help', 'tst');?>
        </a>
        <br />
        
        
        <?php if($post->post_status != 'draft' && $candidates) {?>
        <div class="connected-users">
            <h5><?php _e('Volunteers offered their help', 'tst');?>:</h5>
            <ul>
            <?php foreach($candidates as $candidate) {?>
                <li><?php frl_task_candidate_markup($candidate, 4);?></li>
            <?php }?>
            </ul>
        </div>
        <?php }?>        
    </div>

<?php } elseif((current_user_can('edit_post', get_the_ID()) || $is_curr_users_task) && $post->post_status == 'draft') {?>

    <div id="">
        <a href="#" id="author-publish" class="btn btn-success btn-lg widefat"><?php _e('Publish', 'tst');?></a>
        <br />
        <div id="task-status" class="text-center help-block"><em>(<?php _e('Task is in draft stage', 'tst');?>)</em></div>
        <form id="task-publish">
            <input type="hidden" id="task-id" value="<?php echo get_the_ID();?>" />
            <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('task-publish-by-author');?>" />
            <div id="task-message"></div>
        </form>
    </div>

<?php # } elseif((current_user_can('edit_post', get_the_ID()) || $is_curr_users_task) && $post->post_status == 'publish' && !$candidates) {?>
<?php } elseif($is_curr_users_task && $post->post_status == 'publish' && !$candidates) {?>

    <div id="">
        <a href="#" id="author-unpublish" class="btn btn-danger btn-lg widefat"><?php _e('Stop publication', 'tst');?></a>
        <br />
        <div id="task-status" class="text-center help-block"><em>
            (<?php _e('The task is open. No volunteers found', 'tst');?>)
        </em></div>
        <form id="task-unpublish">
            <input type="hidden" id="task-id" value="<?php echo get_the_ID();?>" />
            <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('task-unpublish-by-author');?>" />
            <div id="task-message"></div>
        </form>
    </div>

<?php # } elseif((current_user_can('edit_post', get_the_ID()) || $is_curr_users_task) && $post->post_status == 'publish' && $candidates) {?>
<?php } elseif($is_curr_users_task && $post->post_status == 'publish' && $candidates) {?>

    <div id="">
        <a href="#" id="task-send-to-work" class="btn btn-success btn-lg widefat"><?php _e('In work!', 'tst');?></a>
        <br />
        <div id="task-status" class="text-center help-block"><em>(<?php _e('The task is open', 'tst');?>)</em></div>
        <form id="task-send-to-work-form">
            <input type="hidden" id="task-id" value="<?php echo get_the_ID();?>" />
            <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('task-send-to-work');?>" />
            <div id="task-message"></div>
        </form>
        <div class="connected-users">
            <h5><?php _e('Volunteers offered their help', 'tst');?>:</h5>
            <ul>
            <?php foreach($candidates as $candidate) {?>
                <li><?php frl_task_candidate_markup($candidate, 1);?></li>
            <?php }?>
            </ul>
        </div>
    </div>

<?php # } else if((current_user_can('edit_post', get_the_ID()) || $is_curr_users_task) && $post->post_status == 'in_work') {?>
<?php } else if($is_curr_users_task && $post->post_status == 'in_work') {?>

    <div id="">
        <a href="#" id="author-close" class="btn btn-danger btn-lg widefat"><?php _e('Close the task', 'tst');?></a>        
        <br />
        <div id="task-status" class="text-center help-block">
            <em>(<?php _e('The task is in work', 'tst');?>)</em> <a href="#" id="author-publish" class=""><?php _e('Re-open the task?', 'tst');?></a>
        </div>
        <form id="task-close">
            <input type="hidden" id="task-id" value="<?php echo get_the_ID();?>" />
            <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('task-close-by-author');?>" />
            <div id="task-message"></div>
        </form>
        <form id="task-publish">
            <input type="hidden" id="task-id" value="<?php echo get_the_ID();?>" />
            <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('task-publish-by-author');?>" />
            <div id="task-message"></div>
        </form>
        <div class="connected-users">
            <?php
            if($candidates) {?>

            <h5><?php _e('Doers and candidates to help', 'tst');?>:</h5>
            <ul>
                <?php foreach($candidates as $candidate) {?>
                    <li><?php frl_task_candidate_markup($candidate, 2);?></li>
                <?php }?>
            </ul>

            <?php } else {
                _e('No doers found.', 'tst');
}
?>
        </div>
    </div>

<?php # } else if((current_user_can('edit_post', get_the_ID()) || $is_curr_users_task) && $post->post_status == 'closed') {?>
<?php } else if($is_curr_users_task && $post->post_status == 'closed') {?>

    <a href="#" id="task-send-to-work" class="btn btn-danger btn-lg widefat"><?php _e('Return to work', 'tst');?></a>
    <br />
    <div id="task-status" class="text-center"><em>(<?php _e('The task is closed', 'tst');?>)</em></div>
    <form id="task-send-to-work-form">
        <input type="hidden" id="task-id" value="<?php echo get_the_ID();?>" />
        <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('task-send-to-work');?>" />
        <div id="task-message"></div>
    </form>
    
        <form id="task-leave-review-form" style="display: none;" class="task-message widefat">
            <p><?php _e('Leave a review to the task doer just to thank him.', 'tst');?></p>
            <div class="form-group">
                <textarea id="review-message" class="form-control" rows="6"></textarea>
                <input type="hidden" id="task-id" value="<?php echo get_the_ID();?>" />
                <input type="hidden" id="doer-id" value="" />
                <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('task-leave-review');?>" />
             </div>
            <div class="form-group">
                <input type="reset" id="cancel-leave-review" value="<?php _e('Cancel', 'tst');?>" class="btn btn-default btn-sm"/>
                <input type="submit" value="<?php _e('Send', 'tst');?>" class="btn btn-success btn-sm"/>
                <img id="add_review_loading" style="display:none;" src="<?=site_url( '/wp-includes/images/spinner-2x.gif' )?>" />
            </div>
            <div id="task-review-message"></div>
        </form>
        <div id="task-review-message-ok-message" class="alert alert-success" style="display:none;"></div>
    
    <div class="connected-users">
        <h5><?php _e('Doers participated', 'tst');?>:</h5>
        <ul>
        <?php foreach($candidates as $candidate) {?>
            <li><?php frl_task_candidate_markup($candidate, 3);?></li>
        <?php }?>
        </ul>
    </div>

<?php } elseif( !$is_curr_users_task && !tst_is_user_candidate() ) {?>

    <div id="">
        <a href="#" id="task-offer-help" class="btn btn-success btn-lg widefat" <?php echo $post->post_status != 'publish' ? 'disabled="disabled"' : '';?>><?php _e('Offer help', 'tst');?></a>        
        <div id="task-status" class="text-center help-block"><em>(
            <?php switch($post->post_status) {
                case 'publish': _e('You are not offered your help yet', 'tst'); break;
                case 'in_work': _e('The task are already in work! Please try offering your help on another one', 'tst'); break;
                case 'closed': _e('The task was closed for help offers', 'tst'); break;
                default:
                    _e('The task is not open yet, so you cannot offer your help for now', 'tst');
            }?>
        )</em></div>
        <form id="task-offer-help-form" style="display: none;" class="task-message widefat">
            <p><?php _e('Send a little message to the task author about what you can do to help.', 'tst');?></p>
            <div class="form-group">
                <textarea id="candidate-message" class="form-control" rows="6"></textarea>
                <input type="hidden" id="task-id" value="<?php echo get_the_ID();?>" />
                <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('task-add-candidate');?>" />
             </div>
            <div class="form-group">
                <input type="reset" id="cancel-sending-message" value="<?php _e('Cancel', 'tst');?>" class="btn btn-default btn-sm"/>
                <input type="submit" value="<?php _e('Send', 'tst');?>" class="btn btn-success btn-sm"/>
            </div>
            <div id="task-message"></div>
        </form>
        <?php if($post->post_status != 'draft' && $candidates) {?>
        <div class="connected-users">
            <h5><?php _e('Volunteers offered their help', 'tst');?>:</h5>
            <ul>
            <?php foreach($candidates as $candidate) {?>
                <li><?php frl_task_candidate_markup($candidate, 4);?></li>
            <?php }?>
            </ul>
        </div>
        <?php }?>
    </div>

<?php } elseif( !$is_curr_users_task && tst_is_user_candidate() >= 1 ) {?>

    <div id="">
        <a href="#" id="task-remove-offer" class="btn btn-danger btn-lg widefat" <?php echo $post->post_status == 'publish' || $post->post_status == 'in_work' ? '' : 'disabled="disabled"'; ?>><?php _e('Refuse helping', 'tst');?></a>
        
        <div id="task-status" class="text-center help-block"><em>(<?php
            switch($post->post_status) {
                case 'publish': _e('You have offered your help, wait for the task author to approve it', 'tst'); break;
                case 'in_work':
                case 'closed': _e('You have offered your help, but task author did not approved it', 'tst'); break;
                default:
                    _e('The task is not open yet, so your help offering is impossible for now', 'tst');
            }
        ?>)</em></div>
        <form id="task-remove-offer-form" style="display: none;" class="task-message widefat">
            <p><?php _e('Send a little message to the task author about why you wish to refuse your help.', 'tst');?></p>
            <div class="form-group">
                <textarea id="candidate-message" class="form-control" rows="6"></textarea>
                <input type="hidden" id="task-id" value="<?php echo get_the_ID();?>" />
                <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('task-remove-candidate');?>" />
            </div>
            <div class="form-group">
                <input type="reset" id="cancel-sending-message" value="<?php _e('Cancel', 'tst');?>" class="btn btn-default btn-sm"/>
                <input type="submit" value="<?php _e('Send', 'tst');?>" class="btn btn-success btn-sm"/>
            </div>
            <div id="task-message"></div>
        </form>
        <div class="connected-users">
            <h5><?php _e('Volunteers offered their help', 'tst');?>:</h5>
            <ul>
                <?php foreach($candidates as $candidate) {?>
                    <li><?php frl_task_candidate_markup($candidate, 5);?></li>
                <?php }?>
            </ul>
        </div>
    </div>

<?php }
