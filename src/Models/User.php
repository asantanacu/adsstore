<?php 

namespace App\Models;

class User
{
    public $id;
    public $email;	
    public $firstname;
    public $lastname;
	public $birth_date;
    public $password;
    public function __construct($data = null)
    {
        if (is_array($data)) {
            if (isset($data['id'])) $this->id = $data['id'];            
            
            $this->email = $data['email'];
			$this->firstname = $data['firstname'];
            $this->lastname = $data['lastname'];
            $this->birth_date = $data['birth_date'];
			$this->password = $data['password'];
        }
    }
}