<?php


namespace App\Admin;

class RolesUnserializer
{
    private $users;

    /**
     * RolesUnserializer constructor.
     * @param $users
     */
    public function __construct(Array $users)
    {
        $this->users=$users;
    }

    public function get()
    {
        $changedUser = [];
        foreach ($this->users as $user) {
            $user['roles'] = unserialize($user['roles']);
            $changedUser[] = $user;
        }
        return $changedUser;
    }
}
