<?php
class Email
{
    private $email;

    public function setemail($email){
        $this->email=$email;
    }

    public function getemail(){
        return $this->email;
    }
}

?>