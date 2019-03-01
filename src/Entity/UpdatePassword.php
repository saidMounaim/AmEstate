<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePassword 
{
    /**
     * String
     * @Assert\NotBlank(message="Old password can't be empty")
     */
    private $oldPassword;

    /**
     * String
     * @Assert\NotBlank(message="New password can't be empty")
     */   
    private $newPassword;

    /**
     * String
     * @Assert\NotBlank(message="Confirm password can't be empty")
     * @Assert\EqualTo(propertyPath="newPassword", message="Confirm password should be equal to new password")
     */
    private $confirmPassword;

    /**
     * Get the value of oldPassword
     */ 
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * Set the value of oldPassword
     *
     * @return  self
     */ 
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * Get the value of newPassword
     */ 
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * Set the value of newPassword
     *
     * @return  self
     */ 
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * Get the value of confirmPassword
     */ 
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    /**
     * Set the value of confirmPassword
     *
     * @return  self
     */ 
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

}