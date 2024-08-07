<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Models;

use System\Classes\Model;

class Category extends Model
{
    public function ForumSearch($query)
    {
        /**
         * tìm kiếm trong bảng `blog` -  trường `title`; bảng `chap` - trường `title`
         * trả về, nếu dữ liệu thuộc bảng blog:
         * ** ['url' => '/forum/id-slug.html, 'title', 'author', 'time']
         * nếu dữ liệu thuộc bảng chap:
         * ** ['url' => '/view-chap/id-slug.html, 'title', 'author', 'time']
         */
        $blogQuery = "
            SELECT 'blog' AS source, id, title, author, time, CONCAT('/forum/', id, '-', slug, '.html') AS url
            FROM blog
            WHERE title LIKE :query
        ";
        $chapQuery = "
            SELECT 'chap' AS source, id, title, author, time, CONCAT('/view-chap/', id, '-', slug, '.html') AS url
            FROM chap
            WHERE title LIKE :query
        ";
        $combinedQuery = "
            ($blogQuery)
            UNION ALL
            ($chapQuery)
            ORDER BY time DESC
        ";

        $stmt = $this->db->prepare($combinedQuery);
        $stmt->execute(['query' => '%' . $query . '%']);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return [
            'count' => count($results),
            'result' => $results
        ];
    }

    public function ForumDetailWithFields($table = null, $fields = null, $value = null)
    {
        if (!$table || !$fields || !$value) {
            return null;
            exit();
        }

        $output = [];
        $stmt = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE ' . $fields . ' = :value LIMIT 1');
        $stmt->execute(['value' => $value]);
        $output = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $output;
    }

    public function ForumCheckExists($table = null, $fields = null, $value = null)
    {
        if (!$table || !$fields || !$value) {
            return null;
            exit();
        }

        $result = false;
        $stmt = $this->db->prepare('
            SELECT COUNT(*) AS count FROM ' . $table . ' WHERE ' . $fields . ' = :value
        ');
        $stmt->execute(['value' => $value]);
        $count = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($count['count'] > 0) {
            $result = true;
        }
        return $result;
    }

    public function ForumStats($table = null, $id = null)
    {
        $counts = [];
        $stats = null;
        if (
            !in_array($table, [
                'count_post_in_category',
                'count_comment_in_post',
                'count_chapter_in_post'
            ])
        ) { // thống kê cục bộ
            # Đếm số hàng trong các bảng
            $stmtCount = $this->db->prepare('
                SELECT
                    (SELECT COUNT(*) FROM category WHERE slug != "news") AS count_category,
                    (SELECT COUNT(*) FROM blog) AS count_post,
                    (SELECT COUNT(*) FROM chap) AS count_chapter,
                    (SELECT COUNT(*) FROM cmt) AS count_comment,
                    (SELECT COUNT(*) FROM users) AS count_user,
                    (SELECT COUNT(*) FROM chat) AS count_chat,
                    (SELECT COUNT(*) FROM users WHERE `on` > :onlimit) AS count_online
            ');
            $stmtCount->execute(['onlimit' => (date('U') - 300)]);
            $counts = $stmtCount->fetch(\PDO::FETCH_ASSOC);
            # Lấy dữ liệu hàng cuối cùng từ bảng users
            $stmtLatestUser = $this->db->prepare('SELECT * FROM users ORDER BY id DESC LIMIT 1');
            $stmtLatestUser->execute();
            $latestUser = $stmtLatestUser->fetch(\PDO::FETCH_ASSOC);
            # Kết hợp kết quả vào một mảng duy nhất$
            $stats = array_merge($counts, ['latest_user' => $latestUser]);
        } else { // đếm bài viết trong category, đếm comment và chaptet trong bài viết
            if ($table === 'count_post_in_category') {
                $sql = "SELECT COUNT(*) AS count FROM blog WHERE `category` = :id";
            } elseif ($table === 'count_comment_in_post') {
                $sql = "SELECT COUNT(*) AS count FROM cmt WHERE `blogid` = :id";
            } else {
                $sql = "SELECT COUNT(*) AS count FROM chap WHERE `box` = :id";
            }

            $stmtCount = $this->db->prepare($sql);
            $stmtCount->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmtCount->execute();
            $counts = $stmtCount->fetch(\PDO::FETCH_ASSOC);
            $stats = $counts['count'];
        }

        return $stats;
    }
    public function CategoryList($limit = 10)
    {
        $stmt = $this->db->prepare('
            SELECT c.*, COUNT(b.id) AS count_post
            FROM category c
            LEFT JOIN blog b ON c.id = b.category
            WHERE c.slug != "news"
            GROUP BY c.id
            ORDER BY c.id ASC
            LIMIT :limit
        ');
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();
        return $data;
    }

    public function CategoryDetail($category_id)
    {
        $stmt = $this->db->prepare('
            SELECT * FROM category WHERE id = :category_id LIMIT 1
        ');
        $stmt->bindParam(':category_id', $category_id, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ?: [];
    }

    public function CategoryPublish($name, $slug, $content, $keyword)
    {
        $stmt = $this->db->prepare('INSERT INTO `category` SET
            `name` = :name,
            `slug` = :slug,
            `content` = :content,
            `keyword` = :keyword
        ');
        $stmt->execute([
            'name' => _e($name),
            'slug' => _e($slug),
            'content' => _e($content),
            'keyword' => _e($keyword),
        ]);

        return $this->db->lastInsertId();
    }

    public function CategoryDelete($category_id)
    {
        // xóa chuyên mục theo id
        $delete_category = $this->db->prepare('DELETE FROM `category` WHERE `id` = :id');
        $delete_category->execute(['id' => $category_id]);
        // lấy danh sách bài viết theo category
        $PostModel = new \App\Models\Post;
        $PostCount = $this->ForumStats('count_post_in_category', $category_id);
        $PostList = $PostModel->PostList($category_id, $PostCount);
        foreach ($PostList as $PostDetail) {
            // xóa bài viết theo id
            $delete_post = $this->db->prepare('DELETE FROM `blog` WHERE `id` = :id');
            $delete_post->execute(['id' => $PostDetail['id']]);
            // xóa comment theo id bài viết
            $delete_comment = $this->db->prepare('DELETE FROM `cmt` WHERE `blogid` = :id');
            $delete_comment->execute(['id' => $PostDetail['id']]);
            // xóa chap theo id bài viết
            $delete_chap = $this->db->prepare('DELETE FROM `chap` WHERE `box` = :id');
            $delete_chap->execute(['id' => $PostDetail['id']]);
        }
        return null;
    }
}