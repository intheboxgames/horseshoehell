<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *  Message library class
 *
 *  Change Log:
 *      
 *      2014-10-06  Luke Stuff   Creation
 */


class Message_library
{
    protected $_messages;

    public function __construct()
    {
        $this->load->library('session');

        $this->_messages = $this->session->userdata('messages');

        if(!$this->_messages || !isset($this->_messages['error'])){
            $this->_messages = array('success' => array(), 'info' => array(), 'warning' => array(), 'error' => array());
        }
    }

    /**
     * Enables the use of CI super-global without having to define an extra variable.
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }

    function store_messages() {
        $this->session->set_userdata('messages', $this->_messages);
    }
    function has_errors() {
        return count($this->_messages['error']) > 0;
    }
    function has_info() {
        return count($this->_messages['info']) > 0;
    }
    function has_warnings() {
        return count($this->_messages['warning']) > 0;
    }
    function has_success() {
        return count($this->_messages['success']) > 0;
    }
    function has_messages($severity = "success") {
        return count($this->_messages[$severity]) > 0;
    }
    function get_messages_array($severity = 'success', $fullText = true) {
        if($fullText){
            $result = array();
            foreach ($this->_messages[$severity] as $message){
                $messageLang = $message;//$this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
                $result[] = $messageLang;
            }
            return $result;
        }
        return $this->_messages[$severity];
    }
    function get_messages($severity = "success") {
        $result = '';
        foreach ($this->_messages[$severity] as $message){
            $messageLang = $message;//$this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
            $result .= '<p>' . $messageLang . '</p>';
        }
        return $result;
    }
    function get_errors() {
        return $this->get_messages('error');
    }
    function get_warnings() {
        return $this->get_messages('warning');
    }
    function get_info() {
        return $this->get_messages('info');
    }
    function get_success() {
        return $this->get_messages('success');
    }
    function add_message($severity = 'success', $message) {
        $this->_messages[$severity][] = $message;
        $this->store_messages();
    }
    function add_error($error) {
        $this->add_message('error', $error);
    }
    function add_warning($warning) {
        $this->add_message('warning', $warning);
    }
    function add_info($info) {
        $this->add_message('info', $info);
    }
    function add_success($success) {
        $this->add_message('success', $success);
    }
    function clear_messages($severity = 'success') {
        if($severity == 'all'){
            $this->_messages['error'] = array();
            $this->_messages['info'] = array();
            $this->_messages['success'] = array();
            $this->_messages['warning'] = array();
        }
        else{
            $this->_messages[$severity] = array();
        }
        $this->store_messages();
    }


}