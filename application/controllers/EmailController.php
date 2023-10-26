<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmailController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    function contactus()
    {
        $config = array(
            array(
                'field'   => 'name',
                'label'   => 'Name',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'email',
                'label'   => 'Email',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'subject',
                'label'   => 'Subject',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'message',
                'label'   => 'Message',
                'rules'   => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'success' => false,
                'error' => array(
                    'name_error' => form_error('name'),
                    'email_error' => form_error('email'),
                    'subject_error' => form_error('subject'),
                    'message_error' => form_error('message'),
                )
            );
        } else {
            $data['data'] = $_POST;
            $html = $this->load->view('email-templates/contact-us-temp', $data, true);
            $subject = $this->input->post('subject');
            if ($this->send_mail($html, $subject)) {
                $data = array(
                    'success' => true,
                    'icon' => 'success',
                    'title' => 'Successfully Submitted!',
                    'msg' => 'We\'ll contact you soon!'
                );
            } else {
                $data = array(
                    'success' => false,
                    'icon' => 'error',
                    'title' => 'Error',
                    'msg' => 'Something went wrong!'
                );
            }
        }
        echo json_encode($data);
    }

    public function send_mail($html = '', $subject, $to_email = 'contact@4kcabletvinternet.com')
    {
        $config = array();
        $config['protocol'] = 'smtp';
        // $config['smtp_crypto'] = 'ssl';
        $config['smtp_host'] = '4kcabletvinternet.com';
        $config['smtp_user'] = 'contact@4kcabletvinternet.com';
        $config['smtp_pass'] = 'Raman@12@12';
        $config['smtp_port'] = '587';
        $config['charset']  = 'UTF-8';
        $config['wordWrap'] = true;
        $config['mailtype'] = 'html';

        $this->load->library('email', $config);

        $from_email = "contact@4kcabletvinternet.com";
        // $to_email = "support@ed-ayurveda.com";
        // $to_email = "fepila8008@abincol.com";

        $this->email->from($from_email, 'Smartick Cable TV Internet Services');
        $this->email->to($to_email);
        $this->email->set_header('Content-Type', 'text/html');
        $this->email->subject($subject);
        $this->email->message($html);
        //Send mail
        // return false;
        if (!$this->email->send())
            return false;
        else
            return true;
    }

    function newsletter()
    {
        $html = $this->load->view('email-templates/news-letter-temp', '', true);
        $subject = 'Ed Ayurveda';
        if ($this->send_mail($html, $subject, $_POST['email'])) {
            $data = array(
                'success' => true,
                'icon' => 'success',
                'title' => 'Successfully Submitted!',
                'msg' => 'Thank you for subscribing our new letter!'
            );
        } else {
            $data = array(
                'success' => false,
                'icon' => 'error',
                'title' => 'Error',
                'msg' => 'Something went wrong!'
            );
        }
        echo json_encode($data);
    }



    function load()
    {
        $this->load->view('email-templates/contact-us-temp');
    }
}
