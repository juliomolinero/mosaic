<?php
namespace User\Model;

use User\Model\User;

interface UserWriteDbInterface
{
    /**
     * Update last login date of an existing user in the system.
     *
     * @param User $user The user to update; must have an identifier.
     * @return User The updated user.
     */
    public function updateUserLastLogin(User $user);
    /**
     *
     * @param number $userId User Identifier
     * @param string $validationCode The new generated validation code
     */
    public function setValidationCode( $userId, $validationCode );
    
    /**
     * Set user a new password
     * @param number $userId
     * @param string $newPwd
     */
    public function setNewPassword( $userId, $newPwd );
}

