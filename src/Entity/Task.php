<?php
// src/Entity/Task.php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Task
{
    /**
     * @Assert\NotBlank()
     */
	protected $firstName;
	
	 /**
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    protected $lastName;

    public function getfirstName()
    {
        return $this->firstName;
    }

    public function setfirstName($firstName)
    {
        $this->firstName = $firstName;
    }
	
	
	public function getlastName()
    {
        return $this->lastName;
    }

    public function setlastName($lastName)
    {
        $this->lastName = $lastName;
    } 	
}