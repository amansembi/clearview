<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $send_token_url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($send_token_url)
    {
       $this->send_token_url = $send_token_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()	
    {		
		$linkarray = explode('/', $this->send_token_url);
		$urldiff = $linkarray[3];
		//return $this->view('send_token_url')->with('data', $urldiff);//$this->send_token_url);
		if($urldiff == 'reset_password'){
			return $this->view('send_token_url')->with('data', $this->send_token_url);
		}else if($urldiff == 'confirm_user'){
			return $this->view('confirm_user')->with('data', $this->send_token_url);
		}else if($urldiff == 'confirm_technician'){
			return $this->view('confirm_technician')->with('data', $this->send_token_url);
		}else if($urldiff == 'login_counter'){
			return $this->view('login_counter')->with('data', $this->send_token_url);
		}
    }
}
