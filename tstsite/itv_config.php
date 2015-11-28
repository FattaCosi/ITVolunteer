<?php
class ItvConfig {
    private $_config;
    private static $_instance = NULL;
    
    public function __construct() {
        
        // change ITV settings here:
        $this->_config = array (
            'ADMIN_EMAILS' => array (
                'support@te-st.ru',
                'suvorov@te-st.ru',
                'denis.cherniatev@gmail.com' 
            ),
            
            'TASK_COMLETE_NOTIF_EMAILS' => array (
                'vlad@te-st.ru',
                'suvorov@te-st.ru',
                'denis.cherniatev@gmail.com' 
            ),
            
            // ITV consultants emails list (for automatic consultation choose)
            'CONSULT_EMAILS' => array (
                'anna.ladoshkina@te-st.ru',
                'suvorov@te-st.ru',
            ),
            'CONSULT_BCC_EMAILS' => array (
                'denis.cherniatev@gmail.com',
                'sidorenko.a@gmail.com',
            ),
            
            'EMAIL_FROM' => 'info@itv.te-st.ru',
            'CONSULT_EMAIL_FROM' => 'support@te-st.ru',
            'TASK_ARCHIVE_DAYS' => 40,
            'TASK_NO_DOER_NOTIF_DAYS' => 9 
        );
    }
    
    public static function instance() {
        if (ItvConfig::$_instance == NULL) {
            ItvConfig::$_instance = new ItvConfig ();
        }
        return ItvConfig::$_instance;
    }
    
    public function get($option_name) {
        return isset ( $this->_config [$option_name] ) ? $this->_config [$option_name] : null;
    }
}
