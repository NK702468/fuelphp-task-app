<?php

class Controller_Tasks extends Controller
{
    // ログインチェック
    public function before()
    {
        parent::before();

        if (!Session::get('user_id'))
        {
            Response::redirect('/auth/login');
        }
    }

    // タスク管理画面
    public function action_index()
    {
        Config::load('app', true);

        $user_id = Session::get('user_id');

        $tasks = Model_Task::find_by_user_id($user_id);

        return Response::forge(
            View::forge('tasks/index', array(
                'tasks' => $tasks,
            ))
        );
    }

    // タスク追加
    public function action_create()
    {
        if (Input::method() !== 'POST') {
            return Response::forge(json_encode(array(
                'success' => false,
                'message' => 'Invalid request',
            )), 405);
        }

        $user_id = Session::get('user_id');

        $title = Input::post('title');
        $due_date = Input::post('due_date');

        if (empty($title) || empty($due_date))
        {
            return Response::forge(json_encode(array(
                'success' => false,
            )));
        }

        $result = Model_Task::create_task($user_id, $title, $due_date);

        $insert_id = $result[0];

        return Response::forge(json_encode(array(
            'success' => true,
            'task' => array(
                'id' => $insert_id,
                'title' => $title,
                'due_date' => $due_date,
                'status' => Config::get('app.task.status_incomplete'),
            ),
        )));
    }

    // タスク更新()
    public function action_update()
    {
        if (Input::method() !== 'POST') {
            return Response::forge(json_encode(array(
                'success' => false,
                'message' => 'Invalid request',
            )), 405);
        }

        $user_id = Session::get('user_id');
        $id = Input::post('id');
        $title = Input::post('title');
        $due_date = Input::post('due_date');

        if (empty($id) || empty($title) || empty($due_date))
        {
            return Response::forge(json_encode(array(
                'success' => false,
                'message' => 'Required fields are missing',
            )), 400);
        }

        Model_Task::update_task($id, $user_id, $title, $due_date);

        return Response::forge(json_encode(array(
            'success' => true,
        )));
    }

    // 完了未完了変更
    public function action_update_status()
    {
        if (Input::method() !== 'POST') {
            return Response::forge(json_encode(array(
                'success' => false,
                'message' => 'Invalid request',
            )), 405);
        }

        $user_id = Session::get('user_id');
        $id = Input::post('id');
        $status = Input::post('status');

        if (!in_array($status, array('0', '1'), true)) {
            return Response::forge(json_encode(array(
                'success' => false,
                'message' => 'Invalid status',
            )), 400);
        }

        Model_Task::update_status($id, $user_id, $status);

        return Response::forge(json_encode(array(
            'success' => true,
        )));
    }

    // タスク削除
    public function action_delete()
    {
        if (Input::method() !== 'POST') {
            return Response::forge(json_encode(array(
                'success' => false,
                'message' => 'Invalid request',
            )), 405);
        }

        $user_id = Session::get('user_id');
        $id = Input::post('id');

        if (empty($id))
        {
            return Response::forge(json_encode(array(
                'success' => false,
                'message' => 'Task id is required',
            )), 400);
        }

        Model_Task::delete_task($id, $user_id);

        return Response::forge(json_encode(array(
            'success' => true,
        )));
    }
}