<?php

namespace App\Domain\User\Data;

/**
 * User session data.
 */
class UserAuthData
{
    /** @var int */
    public $id_user;

    /** @var string */
    public $email;

    /** @var string */
    public $role;
}
