<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Models;

use System\Classes\Model;

class User extends Model
{
    public function UserStats($nick)
    {
        $nick = mb_strtolower($nick);
        $stmt = $this->db->prepare('
            SELECT
                (SELECT COUNT(*) FROM blog WHERE author = :nick) AS count_post,
                (SELECT COUNT(*) FROM chap WHERE author = :nick) AS count_chapter,
                (SELECT COUNT(*) FROM cmt WHERE author = :nick) AS count_comment
        ');
        $stmt->bindParam(':nick', $nick, \PDO::PARAM_STR);
        $stmt->execute();
        $counts = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $counts;
    }

    public function isLoginredirect($request)
    {
        if (!$request->user()->isLogin) {
            redirect('/');
        }
    }

    public function isAdmin120redirect($request)
    {
        $this->isLoginredirect($request);
        $MyDetail = $request->user()->user;
        if ($MyDetail['level'] < 120) {
            redirect('/404');
        }
    }

    public function logout()
    {
        setcookie('cuid', '', TIME - 60, COOKIE_PATH);
        setcookie('cups', '', TIME - 60, COOKIE_PATH);
        unset($_SESSION['uid']);
        unset($_SESSION['ups']);
    }

    // check if user exits for login
    public function getForLogin($type, $email)
    {
        $stmt = $this->db->prepare('SELECT `id`, `pass` FROM `users` WHERE `' . $type . '` = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // check if account or email is exists for register
    public function checkUsedInfo($account, $email)
    {
        $stmt = $this->db->prepare('SELECT `nick`, `email` FROM `users` WHERE nick = :account OR `email` = :email LIMIT 1');
        $stmt->execute(['nick' => $account, 'email' => $email]);
        $data = $stmt->fetch();

        return $data;
    }

    // register
    public function register($user)
    {
        $stmt = $this->db->prepare('INSERT INTO `users` SET
            `nick` = :nick,
            `name` = :name,
            `pass` = :pass,
            `sex` = :sex,
            `reg` = :reg
        ');
        $stmt->execute([
            'nick' => $user['nick'],
            'name' => $user['nick'],
            'pass' => e_pass($user['pass']),
            'sex' => $user['sex'],
            'reg' => TIME
        ]);

        return $this->db->lastInsertId();
    }

    public function UserDetailWithFields($fields = 'nick', $value = 'bot')
    {
        $CategoryModel = new \App\Models\Category;
        
        if ($value === 'bot') {
            $value = env('UserBot');
        }

        $stmt = $CategoryModel->ForumDetailWithFields('users', $fields, $value);
        return $stmt;
    }

    public function UpdateIfNotMD5()
    {
        $select = $this->db->prepare('SELECT * FROM users');
        $select->execute();
        $data = $select->fetchAll();

        foreach ($data as $user) {
            $pass = $user['pass'];
            $strlen = strlen($pass);
            if ($strlen < 15) {
                $pass = md5($pass);
            }
            $update = $this->db->prepare('UPDATE users SET pass = :pass WHERE id = :id');
            $update->bindParam(':pass', $pass, \PDO::PARAM_STR);
            $update->bindParam(':id', $user['id'], \PDO::PARAM_INT);
            $update->execute();
            $update->closeCursor();
        }
    }

    public function UserList($per = 10, $order_by = 'level', $sort = 'desc', $start = 0)
    {
        // Kiểm tra giá trị của $order_by và $sort
        if (!in_array($order_by, config('system.UserList.order_by'))) {
            $order_by = 'level';
        }
        if (!in_array($sort, config('system.UserList.sort'))) {
            $sort = 'desc';
        }

        // Tạo câu SQL dựa trên giá trị của $order_by
        if ($order_by == 'comment') {
            $order_by_sql = '(SELECT COUNT(*) FROM cmt WHERE cmt.author = users.nick)';
        } elseif ($order_by == 'post') {
            $order_by_sql = '((SELECT COUNT(*) FROM blog WHERE blog.author = users.nick COLLATE utf8mb4_unicode_ci) + (SELECT COUNT(*) FROM chap WHERE chap.author = users.nick COLLATE utf8mb4_unicode_ci))';
        } else {
            $order_by_sql = 'users.' . $order_by;
        }

        // SQL query để lấy danh sách người dùng theo thứ tự
        $sql = 'SELECT users.*, ' . $order_by_sql . ' AS order_value FROM users ORDER BY order_value ' . $sort . ' LIMIT :start, :per';

        // Chuẩn bị câu lệnh SQL
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':start', $start, \PDO::PARAM_INT);
        $stmt->bindParam(':per', $per, \PDO::PARAM_INT);

        // Thực thi câu lệnh
        $stmt->execute();

        // Lấy tất cả kết quả
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    public function UserListOnline($start = 0, $end = 0)
    {
        $onlimit = date('U') - 300;
        $sql = 'SELECT * FROM users WHERE `on` > :onlimit';
        if ($end > 0) {
            $sql .= ' LIMIT :start, :end';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':onlimit', $onlimit, \PDO::PARAM_INT);
        if ($end > 0) {
            $stmt->bindParam(':start', $start, \PDO::PARAM_INT);
            $stmt->bindParam(':end', $end, \PDO::PARAM_INT);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    public function UserOnlineStatus($UserDetail)
    {
        $onlimit = date('U') - 300;
        $status = 'offline';
        if ($UserDetail['on'] > $onlimit) {
            $status = 'online';
        }
        return $status;
    }

    public function UserDetailEdit($MyDetail, $SaveData, $table = 'users')
    {
        $sql = "UPDATE $table SET ";
        $setParts = [];
        $bindValues = [];
        foreach ($SaveData as $key => $value) {
            $setParts[] = "`$key` = :$key";
            $bindValues[":$key"] = $value;
        }
        $sql .= implode(', ', $setParts);
        $sql .= ' WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $bindValues[':id'] = $MyDetail['id'];

        foreach ($bindValues as $param => $val) {
            $stmt->bindValue($param, $val);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function UserDetailBlockList($UserDetail)
    {
        if (!isset($UserDetail['blocklist']) || !is_string($UserDetail['blocklist'])) {
            return [
                'Get' => [],
                'Count' => 0
            ];
        }
        $BlockList = explode('.', $UserDetail['blocklist']);
        $BlockList = array_filter($BlockList, function ($value) {
            return $value !== '';
        });
        $CountBlocked = count($BlockList);
        return [
            'Get' => array_values($BlockList),
            'Count' => $CountBlocked
        ];
    }

}
