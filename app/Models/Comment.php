<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Models;

use System\Classes\Model;

class Comment extends Model
{
    public function CommentList($post_id, $start, $end)
    {
        // SQL query to fetch blog posts and category details if available
        $sql = '
            SELECT * FROM cmt WHERE blogid = :post_id ORDER BY id asc LIMIT :start, :per
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, \PDO::PARAM_INT);
        $stmt->bindParam(':start', $start, \PDO::PARAM_INT);
        $stmt->bindParam(':per', $end, \PDO::PARAM_INT);
        // Execute the query
        $stmt->execute();
        // Fetch all results
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    public function CommentSend($PostDetail, $author, $comment)
    {
        // lưu bình luận
        $stmt = $this->db->prepare('INSERT INTO `cmt` SET
            `blogid` = :blogid,
            `author` = :author,
            `comment` = :comment,
            `time` = :time
        ');
        $stmt->execute([
            'blogid' => $PostDetail['id'],
            'author' => $author,
            'comment' => $comment,
            'time' => TIME
        ]);
        // cập nhật thời gian cho post
        $stmt_post = $this->db->prepare('UPDATE `blog` SET `update_time` = :update_time WHERE `id` = :id');
        $stmt_post->execute([
            'update_time' => TIME,
            'id' => $PostDetail['id']
        ]);
        if ($author != $PostDetail['author']) {
            // lưu vào thông báo của author post
            $system_notify_content = '@' . $author . ' đã bình luận trong một bài viết của bạn. [url=/forum/' . $PostDetail['id'] . '-' . $PostDetail['slug'] . '.html][XEM BÀI VIẾT] ' . $PostDetail['title'] . '[/url]';
            $stmt_system_notify = $this->db->prepare('INSERT INTO `mail` SET
                `sender_receiver` = :sender_receiver,
                `nick` = :nick,
                `content` = :content,
                `time` = :time,
                `view` = :view
        ');
            $stmt_system_notify->execute([
                'sender_receiver' => $PostDetail['author'] . '_' . env('UserBot'),
                'nick' => $author,
                'content' => $system_notify_content,
                'time' => TIME,
                'view' => 'no'
            ]);
        }
        return null;
    }
}