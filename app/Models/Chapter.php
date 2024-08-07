<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Models;

use System\Classes\Model;

class Chapter extends Model
{
    public function ChapterList($post_id)
    {
        // SQL query to fetch blog posts and category details if available
        $sql = 'SELECT * FROM chap';
        if ($post_id > 0) {
            $sql.=' WHERE box = :post_id';
        }
        $sql .= ' ORDER BY id desc';
        $stmt = $this->db->prepare($sql);
        if ($post_id > 0) {
            $stmt->bindParam(':post_id', $post_id, \PDO::PARAM_INT);
        }
        // Execute the query
        $stmt->execute();
        // Fetch all results
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    public function ChapterEdit($id, $data)
    {
        $stmt = $this->db->prepare('UPDATE `chap` SET
            `title` = :title,
            `slug` = :slug,
            `content` = :content
        WHERE `id` = :id
        ');
        $stmt->execute([
            'title' => _e($data['title']),
            'slug' => _e($data['slug']),
            'content' => _e($data['content']),
            'id' => $id
        ]);
        return $stmt->rowCount();
    }

    public function ChapterDelete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM `chap` WHERE `id` = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    public function ChapterPublish($box, $title, $slug, $content, $author)
    {
        $stmt = $this->db->prepare('INSERT INTO `chap` SET
            `box` = :box,
            `title` = :title,
            `slug` = :slug,
            `content` = :content,
            `author` = :author,
            `time` = :time
        ');
        $stmt->execute([
            'box' => $box,
            'title' => _e($title),
            'slug' => _e($slug),
            'content' => _e($content),
            'author' => $author,
            'time' => TIME
        ]);
        return $this->db->lastInsertId();
    }
}