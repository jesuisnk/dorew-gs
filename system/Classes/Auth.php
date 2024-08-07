<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace System\Classes;

use PDO;

class Auth
{
    public $id = 0;
    public $level = 0;
    public $isLogin = false;
    public $user = [
        'id' => 0,
        'email' => '',
        'nick' => '',
        'name' => '',
        'pass' => '',
        'sex' => '',
        'avatar' => '',
        'cover' => '',
        'new_mail' => '',
        'mail_list' => '',
        'blocklist' => '',
        'level' => 0,
        'reg' => 0,
        'on' => 0,
        'login' => '',
        'status' => '',
        'karma' => 0
    ];
    public $new_mail_count = 0;
    public $system_notify_count = 0;

    public $settings;

    public function __construct(protected PDO $db)
    {
        $this->authorize();
    }

    private function authorize()
    {
        $id = 0;
        $password = '';

        if (isset($_SESSION['uid']) && isset($_SESSION['ups'])) {
            $id = intval(trim($_SESSION['uid']));
            $password = trim($_SESSION['ups']);
        } elseif (isset($_COOKIE['cuid']) && isset($_COOKIE['cups'])) {
            $id = intval(base64_decode(trim($_COOKIE['cuid'])));
            $password = md5(trim($_COOKIE['cups']));
            $_SESSION['uid'] = $id;
            $_SESSION['ups'] = $password;
        }

        if ($id && $password) {
            $stmt = $this->db->prepare('SELECT * FROM `users` WHERE `id` = ? LIMIT 1');
            $stmt->execute([$id]);
            $user = $stmt->fetch();

            if ($user) {
                if ($password === $user['pass']) {
                    $this->isLogin = true;
                    $this->id = (int) $user['id'];
                    $this->level = (int) $user['level'];
                    $this->user = $user;
                    $this->new_mail_count = $this->NewMailCount($user);
                    $this->system_notify_count = $this->SystemNotifyCount($user);

                    if ($user['level'] < 0) {
                        die('<div style="text-align: center; font-size: xx-large">'
                            . '<h3 style="color: #dd0000">Account Suspended</h3>'
                            . 'Tài khoản của bạn đã bị dừng hoạt động do vi phạm Nội quy diễn đàn!'
                            . '</div>');
                    }

                    $this->db->prepare('UPDATE `users` SET
                        `on`   = ?
                        WHERE `id` = ? LIMIT 1
                    ')->execute([TIME, $user['id']]);
                } else {
                    $this->unset();
                }
            } else {
                $this->unset();
            }
        }
    }

    private function unset()
    {
        unset($_SESSION['uid']);
        unset($_SESSION['ups']);
        setcookie('cuid', '', TIME - 60, COOKIE_PATH);
        setcookie('cups', '', TIME - 60, COOKIE_PATH);
    }

    public function NewMailCount($MyDetail)
    {
        $count = 0;
        if ($MyDetail['new_mail']) {
            $getMailList = explode('.', $MyDetail['new_mail']);
            $getMailList = array_filter($getMailList, function ($value) {
                return $value !== null && $value !== false && $value !== "";
            });
            $count = count($getMailList);
        }
        return $count;
    }

    public function SystemNotifyCount($MyDetail)
    {
        $stmtCount = $this->db->prepare('
            SELECT COUNT(*) as count 
            FROM mail 
            WHERE sender_receiver = ? AND view = ?
        ');
        $stmtCount->execute([$MyDetail['nick'] . '_' . env('UserBot'), 'no']);
        $result = $stmtCount->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
