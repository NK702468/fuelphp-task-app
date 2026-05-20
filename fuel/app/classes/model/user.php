<?php

class Model_User extends \Model
{
    public static function find_by_email($email)
    {
        return \DB::select()
            ->from('users')
            ->where('email', $email)
            ->execute()
            ->current();
    }

    public static function create_user($name, $email, $hashed_password)
    {
        return \DB::insert('users')
            ->set(array(
                'name' => $name,
                'email' => $email,
                'password' => $hashed_password,
            ))
            ->execute();
    }
}