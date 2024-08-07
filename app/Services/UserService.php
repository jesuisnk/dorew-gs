<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Services;

class UserService
{
    public function validateAccount($account)
    {
        if (empty($account)) {
            return 'Tên tài khoản không được để trống';
        }

        $len = mb_strlen($account);

        if ($len < 3 || $len > 32) {
            return 'Độ dài tên tài khoản phải từ 3 đến 15 ký tự';
        }

        if (preg_match('/[^0-9a-z]/i', $account)) {
            return 'Tên tài khoản chỉ được sử dụng chữ latin, và các chữ số';
        }

        return false;
    }


    public function validatePassword($password)
    {
        if (empty($password)) {
            return 'Mật khẩu không được để trống';
        }

        $len = mb_strlen($password);

        if ($len < 3 || $len > 32) {
            return 'Độ dài mật khẩu phải từ 3 đến 32 ký tự';
        }

        return false;
    }

    public function validatePasswordConfirmation($password, $passwordConfirmation)
    {
        if ($password !== $passwordConfirmation) {
            return 'Mật khẩu không trùng khớp';
        }

        return false;
    }

    public function validateName($name)
    {
        if (empty($name)) {
            return 'Tên hiển thị không được để trống';
        }

        $len = mb_strlen($name);

        if ($len < 4 || $len > 32) {
            return 'Độ dài tên hiển thị phải từ 5 đến 32 ký tự';
        }

        if (preg_match('/[^[:alnum:]\s]/ui', $name)) {
            return 'Tên hiển thị chỉ có thể sử dụng chữ cái (có dấu), chữ số và khoảng trắng';
        }

        return false;
    }
}
