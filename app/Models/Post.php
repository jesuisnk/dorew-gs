<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Models;

use System\Classes\Model;

class Post extends Model
{
    public function PostList($category_id = 0, $per = 10, $order_by = 'update_time', $sort = 'desc', $start = 0)
    {
        // SQL query to fetch blog posts and category details if available
        $sql = 'SELECT b.*, 
                       (CASE WHEN c.id IS NOT NULL THEN c.id ELSE NULL END) AS category_id,
                       (CASE WHEN c.id IS NOT NULL THEN c.name ELSE NULL END) AS category_name
                FROM blog b
                LEFT JOIN category c ON b.category = c.id';

        // Append conditions based on category_id
        if ($category_id > 0) {
            $sql .= ' WHERE c.id = :category_id';
        }

        if (!in_array($order_by, config('system.PostList.order_by'))) {
            $order_by = 'update_time';
        }
        if (!in_array($sort, config('system.PostList.sort'))) {
            $sort = 'desc';
        }

        // Append order and limit clauses
        $sql .= ' ORDER BY ' . $order_by . ' ' . $sort . ' 
                  LIMIT :start, :per';

        // Prepare the SQL statement
        $stmt = $this->db->prepare($sql);

        // Bind parameters
        if ($category_id > 0) {
            $stmt->bindParam(':category_id', $category_id, \PDO::PARAM_INT);
        }
        $stmt->bindParam(':start', $start, \PDO::PARAM_INT);
        $stmt->bindParam(':per', $per, \PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch all results
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    public function PostListSimilar($category_id, $post_id)
    {
        $stmt = $this->db->prepare('
            SELECT * FROM blog WHERE category = :category_id AND id!= :post_id ORDER BY RAND() LIMIT 5
        ');
        $stmt->bindParam(':category_id', $category_id, \PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $post_id, \PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    public function PostListUser($nick, $start = 0, $end = 10)
    {
        $stmt = $this->db->prepare('
            SELECT b.*, 
                       (CASE WHEN c.id IS NOT NULL THEN c.id ELSE NULL END) AS category_id,
                       (CASE WHEN c.id IS NOT NULL THEN c.name ELSE NULL END) AS category_name
                FROM blog b
                LEFT JOIN category c ON b.category = c.id
                WHERE author = :nick 
                ORDER BY update_time 
                DESC LIMIT :start, :end
        ');
        $stmt->bindParam(':nick', $nick, \PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, \PDO::PARAM_INT);
        $stmt->bindParam(':end', $end, \PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    public function PostCountUser($nick)
    {
        $stmt = $this->db->prepare('
            SELECT COUNT(*) as count FROM blog WHERE author = :nick
        ');
        $stmt->bindParam(':nick', $nick, \PDO::PARAM_STR);
        $stmt->execute();
        $counts = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stats = $counts['count'];
        return $stats;
    }

    public function PostDetail($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM blog WHERE id = :id LIMIT 1');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $output = $stmt->fetchAll()[0];
        return $output;
    }

    public function PostLikeList($id, $action = null)
    {
        $UserModel = new \App\Models\User;
        $PostDetail = $this->PostDetail($id);
        $PostLikeList = $PostDetail['like'] ? array_filter(explode('.', $PostDetail['like'])) : [];
        $PostLikeCount = count($PostLikeList);
        $output = [];
        if ($PostLikeCount > 0) {
            foreach ($PostLikeList as $nick) {
                $UserDetail = $UserModel->UserDetailWithFields('nick', $nick);
                $output[] = $UserDetail;
            }
        }
        if ($action == 'count') {
            return $PostLikeCount;
        } else {
            return $output;
        }
    }
    public function PostLikeSave($id, $nick, $action)
    {
        $result = false;
        $nick = mb_strtolower($nick);
        $PostDetail = $this->PostDetail($id);
        $PostLikeList = $PostDetail['like'] ? explode('.', $PostDetail['like']) : [];
        $PostLikeListSave = $nick . '.' . $PostDetail['like'];
        if ($action == 'save') {
            if (!in_array($nick, $PostLikeList) && $nick != $PostDetail['author']) {
                // lưu vào bảng `blog`
                $stmt_post = $this->db->prepare('
                UPDATE `blog` SET `like` = :like 
                WHERE `id` = :id
            ');
                $stmt_post->execute(['id' => $id, 'like' => $PostLikeListSave]);
                // lưu vào thông báo của author
                $system_notify_content = '@' . $nick . ' vừa bày tỏ cảm xúc trong một bài viết có mặt bạn. [url=/forum/' . $id . '-' . $PostDetail['slug'] . '.html][XEM BÀI VIẾT][/url]';
                $stmt_system_notify = $this->db->prepare('INSERT INTO `mail` SET
                    `sender_receiver` = :sender_receiver,
                    `nick` = :nick,
                    `content` = :content,
                    `time` = :time,
                    `view` = :view
                ');
                $stmt_system_notify->execute([
                    'sender_receiver' => $PostDetail['author'] . '_' . env('UserBot'),
                    'nick' => $nick,
                    'content' => $system_notify_content,
                    'time' => TIME,
                    'view' => 'no'
                ]);
                $result = true;
            }
        } elseif ($action == 'check') {
            if (in_array($nick, $PostLikeList) || $nick == $PostDetail['author']) {
                $result = true;
            }
        }
        return $result;
    }

    public function PostSaveOne($id, $col, $cold_val)
    {
        $stmt = $this->db->prepare('UPDATE `blog` SET `' . $col . '` = :col_value WHERE `id` = :id');
        $stmt->bindValue(':col_value', $cold_val, \PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function PostPublish($category, $title, $slug, $content, $author)
    {
        $stmt = $this->db->prepare('INSERT INTO `blog` SET
            `category` = :category,
            `title` = :title,
            `slug` = :slug,
            `content` = :content,
            `author` = :author,
            `time` = :time,
            `update_time` = :update_time
        ');
        $stmt->execute([
            'category' => $category,
            'title' => _e($title),
            'slug' => _e($slug),
            'content' => _e($content),
            'author' => $author,
            'time' => TIME,
            'update_time' => TIME
        ]);
        return $this->db->lastInsertId();
    }

    public function PostEdit($id, $data)
    {
        $stmt = $this->db->prepare('UPDATE `blog` SET
            `category` = :category,
            `title` = :title,
            `slug` = :slug,
            `content` = :content
        WHERE `id` = :id
        ');
        $stmt->execute([
            'category' => $data['category'],
            'title' => _e($data['title']),
            'slug' => _e($data['slug']),
            'content' => _e($data['content']),
            'id' => $id
        ]);
        return $stmt->rowCount();
    }

    public function PostDelete($id)
    {
        // xóa bài viết theo id
        $delete_post = $this->db->prepare('DELETE FROM `blog` WHERE `id` = :id');
        $delete_post->execute(['id' => $id]);
        // xóa comment theo id bài viết
        $delete_comment = $this->db->prepare('DELETE FROM `cmt` WHERE `blogid` = :id');
        $delete_comment->execute(['id' => $id]);
        // xóa chap theo id bài viết
        $delete_chap = $this->db->prepare('DELETE FROM `chap` WHERE `box` = :id');
        $delete_chap->execute(['id' => $id]);
        // xóa file theo id bài viết
        $delete_file = $this->db->prepare('DELETE FROM `file` WHERE `blogid` = :id');
        $delete_file->execute(['id' => $id]);
    }

    public function PostViewUpdate($PostDetail)
    {
        $PostID = $PostDetail['id'];
        if (!isset($_SESSION['post_viewed_' . $PostID])) {
            $stmt = $this->db->prepare('UPDATE `blog` SET `view` = :view WHERE `id` = :id');
            $stmt->execute([
                'view' => $PostDetail['view'] + 1,
                'id' => $PostDetail['id']
            ]);
            $_SESSION['post_viewed_' . $PostID] = true;
        }
        return $PostDetail['view'];
    }
}