<?php
namespace User\Model;

interface UserReadDbInterface
{
    /**
     * Return a single user
     *
     * @param string $email User email
     * @return User
     */
    public function findUserEmail( $email );
}

