<?php

class Model_Task extends \Model
{
    public static function find_by_user_id($user_id)
    {
        return \DB::select()
            ->from('tasks')
            ->where('user_id', $user_id)
            ->order_by('due_date', 'asc')
            ->execute()
            ->as_array();
    }

    public static function create_task($user_id, $title, $due_date)
    {
        return \DB::insert('tasks')
            ->set(array(
                'user_id' => $user_id,
                'title' => $title,
                'due_date' => $due_date,
                'status' => 0,
            ))
            ->execute();
    }

    public static function update_task($id, $user_id, $title, $due_date)
    {
        return \DB::update('tasks')
            ->set(array(
                'title' => $title,
                'due_date' => $due_date,
                'updated_at' => date('Y-m-d H:i:s'),
            ))
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->execute();
    }

    public static function update_status($id, $user_id, $status)
    {
        return \DB::update('tasks')
            ->set(array(
                'status' => (int)$status,
                'updated_at' => date('Y-m-d H:i:s'),
            ))
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->execute();
    }

    public static function delete_task($id, $user_id)
    {
        return \DB::delete('tasks')
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->execute();
    }
}