<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Models;

use System\Classes\Model;

class Shoutbox extends Model
{
    // các chức năng
    public function ChatSend($user, $msg)
    {
        $stmt = $this->db->prepare('INSERT INTO `chat` SET
            `name` = :name,
            `time` = :time,
            `comment` = :comment
        ');
        $stmt->execute([
            'name' => $user['nick'],
            'time' => TIME,
            'comment' => _e($msg)
        ]);

        return null;
    }

    // lấy thông tin
    public function ChatDetail($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM `chat` WHERE `id` = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetchAll()[0];
        return $data;
    }

    public function ChatList($start, $end)
    {
        // SQL query to fetch chat details if available
        $sql = '
            SELECT * FROM chat ORDER BY id desc LIMIT :start, :per
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':start', $start, \PDO::PARAM_INT);
        $stmt->bindParam(':per', $end, \PDO::PARAM_INT);
        // Execute the query
        $stmt->execute();
        // Fetch all results
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }
}