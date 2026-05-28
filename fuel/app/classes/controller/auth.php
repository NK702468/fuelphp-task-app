<?php

class Controller_Auth extends Controller
{
    // ユーザー登録処理
    public function action_register()
    {
        $error = '';

        if (Input::method() === 'POST')
        {
            $name = Input::post('name');
            $email = Input::post('email');
            $password = Input::post('password');
            $password_confirm = Input::post('password_confirm');

            if ($password !== $password_confirm)
            {
                $error = 'パスワードが一致しません';
            }
            else
            {
                $existing_user = Model_User::find_by_email($email);

                if ($existing_user)
                {
                    $error = 'このメールアドレスは既に登録されています';
                }
                else
                {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    Model_User::create_user($name, $email, $hashed_password);

                    Response::redirect('/auth/login');
                }
            }
        }

        return Response::forge(
            View::forge('users/register', array(
                'error' => $error,
            ))
        );
    }

    // ログイン処理
    public function action_login()
    {
        $error = '';

        if (Input::method() === 'POST')
        {
            $email = Input::post('email');
            $password = Input::post('password');

            $user = Model_User::find_by_email($email);

            if (!$user)
            {
                $error = 'メールアドレスが存在しません';
            }
            elseif (!password_verify($password, $user['password']))
            {
                $error = 'パスワードが違います';
            }
            else
            {
                Session::rotate();
                
                Session::set('user_id', $user['id']);
                Session::set('user_name', $user['name']);

                Response::redirect('/tasks');
            }
        }

        return Response::forge(
            View::forge('users/login', array(
                'error' => $error,
            ))
        );
    }
}