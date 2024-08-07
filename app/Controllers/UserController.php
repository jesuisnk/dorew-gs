<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Services\UserService;
use System\Classes\Captcha;
use System\Classes\Controller;
use System\Classes\Request;

class UserController extends Controller
{
    public function __construct(
        protected User $UserModel,
        protected Post $PostModel,
        protected Category $CategoryModel,
        protected UserService $UserService
    ) {
    }

    public function logout()
    {
        $this->UserModel->logout();
        redirect('/');
    }

    public function login(Request $request)
    {
        if ($request->user()->isLogin) {
            redirect('/');
        }

        $error = false;
        $account = $request->postVar('account', '');
        $password = $request->postVar('password', '');
        $remember = $request->postVar('remember', 0);
        $doomcaptcha = $request->postVar('doomcaptcha', 0);

        if ($request->getMethod() === 'POST') {
            // reset token
            $token = $request->postVar('csrf_token', '');
            $checktoken = isCSRFTokenValid($token);
            if ($checktoken) {
                $error = 'Invalid token';
            }
            unsetCSRFToken();
            generateCSRFToken();

            if (empty($account) || empty($password)) {
                $error = 'Vui lòng nhập tên tài khoản và mật khẩu';
            } elseif (!$doomcaptcha) {
                $error = 'Vui lòng xác thực captcha';
            } else {
                $error = $this->UserService->validateAccount($account);
                if (!$error) {
                    $error = $this->UserService->validatePassword($password);
                }

                if (!$error) {
                    $user = $this->UserModel->UserDetailWithFields('nick', $account);
                    //die($this->UserService->e_pass($password));
                    if ($user && e_pass($password) === $user['pass']) {
                        $_SESSION['uid'] = $user['id'];
                        $_SESSION['ups'] = $user['pass'];

                        if ($remember) {
                            // Save cookie (365 day)
                            setcookie('cuid', base64_encode($user['id']), TIME + 31536000, COOKIE_PATH);
                            setcookie('cups', e_pass($password, 1), TIME + 31536000, COOKIE_PATH);
                        }
                        redirect('/');
                    } else {
                        $error = 'Tên tài khoản hoặc mật khẩu không chính xác';
                    }
                }
            }
        }

        return view()
            ->setTitle('Đăng nhập')
            ->render('user/login', [
                'error' => $error,
                'inputAccount' => _e($account),
                'inputRemember' => $remember,
                'inputCaptcha' => $doomcaptcha
            ]);
    }

    public function register(Request $request)
    {
        if ($request->user()->isLogin) {
            redirect('/');
        }

        $error = [];
        $captcha = app(Captcha::class);
        $account = $request->postVar('account', '');
        $password = $request->postVar('password', '');
        $re_password = $request->postVar('re_password', '');
        $sex = $request->postVar('sex', 'girl');

        if ($request->getMethod() === 'POST') {
            // reset token
            $token = $request->postVar('csrf_token', '');
            $checktoken = isCSRFTokenValid($token);
            if ($checktoken) {
                $error[] = 'Invalid token';
            }
            unsetCSRFToken();
            generateCSRFToken();

            // check account
            $check = $this->UserService->validateAccount($account);
            if ($check) {
                $error[] = $check;
            }

            // check password
            $check = $this->UserService->validatePassword($password);
            if ($check) {
                $error[] = $check;
            }

            // check repeat password
            $check = $this->UserService->validatePasswordConfirmation($password, $re_password);
            if ($check) {
                $error[] = $check;
            }

            // check captcha
            if ($captcha->check() !== true) {
                $error[] = 'Mã bảo vệ không chính xác';
            }

            if (!$error) {
                $check = $this->UserModel->UserDetailWithFields('nick', $account);
                if ($check) {
                    $error[] = 'Tên tài khoản đã được sử dụng';
                }
            }

            $sex = mb_strtolower($sex);
            $account = mb_strtolower($account);

            if (!$error) {
                if (!in_array($sex, ['boy', 'girl'])) {
                    $error[] = 'Giới tính được chọn không hợp lệ';
                }
            }

            if (!$error) {
                $user_id = $this->UserModel->register([
                    'nick' => $account,
                    'pass' => $password,
                    'sex' => $sex
                ]);

                $_SESSION['uid'] = $user_id;
                $_SESSION['ups'] = e_pass($password);
                redirect('/');
            } else {
                $error = display_error($error);
            }
        }

        return view()
            ->setTitle('Đăng ký')
            ->render('user/register', [
                'error' => $error,
                'inputAccount' => _e($account),
                'inputSex' => _e($sex)
            ]);
    }

    public function UserList(Request $request)
    {
        // lọc bài viết
        $UserCount = $this->CategoryModel->ForumStats()['count_user'];
        $page_query = '?';
        # danh sách thành viên có trên 1 trang
        $per = 10;
        $page = $request->getVar('page', 1);
        $page = htmlspecialchars($page);
        $page = intval($page);
        # lọc theo trường
        $order_by = $request->getVar('order_by', 'level');
        $order_by = mb_strtolower($order_by);
        $order_by = htmlspecialchars($order_by);
        if (in_array($order_by, config('system.UserList.order_by'))) {
            $page_query .= 'order_by=' . $order_by . '&';
        } else {
            $order_by = 'level';
        }
        # hạn chế lọc đối với != level
        if ($order_by != 'level') {
            $page = 1;
            $sort = 'desc';
        }
        # sắp xếp bài viết theo trường đã lọc
        $sort = $request->getVar('sort', 'desc');
        $sort = mb_strtolower($sort);
        $sort = htmlspecialchars($sort);
        if (in_array($sort, config('system.UserList.sort'))) {
            $page_query .= 'sort=' . $sort . '&';
        } else {
            $sort = 'desc';
        }
        # thêm số trang
        if ($page >= 1) {
            $page_query .= 'page=';
        }

        $page_max = ceil($UserCount / $per);
        $start = ($page - 1) * $per;
        $end = $start + $per;
        if ($end >= $UserCount) {
            $end = $UserCount;
        }

        // lấy danh sách thành viên theo bộ lọc và sắp xếp
        $getUserList = $this->UserModel->UserList($per, $order_by, $sort, $start);
        //die(print_r($getUserList));
        $UserList = [];
        foreach ($getUserList as $UserDetail) {
            $UserStats = $this->UserModel->UserStats($UserDetail['nick']);
            $UserDetail['UserStats'] = $UserStats;
            $UserStatsInfo = [
                'level' => $UserDetail['level'],
                'post' => $UserStats['count_post'] + $UserStats['count_chapter'],
                'comment' => $UserStats['count_comment']
            ];
            //die(print_r($UserStatsInfo));
            $UserDetail['UserStatsInfo'] = ucfirst($order_by) . ': <b>' . $UserStatsInfo[$order_by] . '</b>';

            $UserList[] = $UserDetail;
        }
        $page_title = [
            'level' => 'Thành viên',
            'post' => 'Top diễn đàn',
            'comment' => 'Top bình luận'
        ];
        $page_title = $page_title[$order_by];

        return view()
            ->setTitle($page_title)
            ->render('user/user_list', [
                'page_title' => $page_title,
                'UserCount' => $UserCount,
                'UserList' => $UserList,
                'UserListPaging' => paging($page_query, $page, $page_max),
                'UserListConfig' => [
                    'allow_order_by' => config('system.UserList.order_by'),
                    'allow_sort' => config('system.UserList.sort'),
                    'order_by' => $order_by,
                    'sort' => $sort
                ],
            ]);
    }

    public function UserDetail($UriAccount, Request $request)
    {
        if (!$request->user()->isLogin) {
            redirect('/login');
        }

        /**
         * MyDetail: thông tin của thành viên đăng nhập
         * YourDetail: thông tin của trang cá nhân đang truy cập
         */

        $template = 'user/user_detail';
        $MyDetail = $request->user()->user;
        if (empty($UriAccount)) {
            $YourDetail = $request->user()->user;
        } else {
            $UserDetailUri = explode('/', $UriAccount);
            // thông tin hồ sơ
            $account = $UserDetailUri[1];
            $account = trim($account);
            $account = mb_strtolower($account);
            $YourDetail = $this->UserModel->UserDetailWithFields('nick', $account);
            if (isset($account) && !$YourDetail) {
                redirect('/404');
            }
            // chỉnh sửa hồ sơ
            $UserDetailEditType = isset($UserDetailUri[2]) ? $UserDetailUri[2] : null;
            if (
                isset($UserDetailEditType)
                && in_array($UserDetailEditType, ['info', 'avatar', 'cover', 'password', 'blocklist'])
                && $MyDetail['nick'] == $YourDetail['nick']
            ) {
                $YourDetail = $MyDetail;
                $template = 'user/user_detail_edit';
                $ArrayData = $this->UserDetailEdit($MyDetail, $UserDetailEditType, $request);
                $ArrayData = array_merge($ArrayData, [
                    'MyDetail' => $MyDetail
                ]);
            }
        }

        $action = $request->getVar('action');
        $PostCount = $this->PostModel->PostCountUser($YourDetail['nick']);
        $getPostList = $this->PostModel->PostListUser($YourDetail['nick'], 0, 10);
        $PostList = [];
        foreach ($getPostList as $PostDetail) {
            $author = mb_strtolower($PostDetail['author']);
            $PostDetail['UserDetail'] = $this->UserModel->UserDetailWithFields('nick', $author);
            $PostDetail['chapter'] = $this->CategoryModel->ForumStats('count_chapter_in_post', $PostDetail['id']);
            $PostDetail['comment'] = $this->CategoryModel->ForumStats('count_comment_in_post', $PostDetail['id']);

            $PostList[] = $PostDetail;
        }

        if ($template == 'user/user_detail') {
            $ArrayData = [
                'MyDetail' => $MyDetail,
                'YourDetail' => $YourDetail,

                'action' => $action,
                'PostList' => $PostList,
                'PostCount' => $PostCount
            ];
        }

        return view()
            ->setTitle('Trang cá nhân: ' . $YourDetail['name'])
            ->render($template, $ArrayData);
    }

    private function UserDetailEdit($MyDetail, $UserDetailEditType, $request)
    {
        /**
         * Chỉnh sửa hồ sơ
         * $this->UserDetail => $MyDetail
         * type: info, avatar, cover, change.blocklist, change.password
         */

        $error = null;

        $status = $request->postVar('status', $MyDetail['status']);
        $avatar = $request->postVar('avatar', '');
        $cover = $request->postVar('cover', $MyDetail['cover']);
        $name = $request->postVar('name', $MyDetail['name']);
        $sex = $request->postVar('sex', $MyDetail['sex']);

        $old_password = $request->postVar('old_password', '');
        $new_password = $request->postVar('new_password', '');
        $re_password = $request->postVar('re_password', '');

        if ($request->getMethod() === 'POST') {
            // reset token
            $token = $request->postVar('csrf_token', '');
            $checktoken = isCSRFTokenValid($token);
            if ($checktoken) {
                $error = 'Invalid token';
            }
            unsetCSRFToken();
            generateCSRFToken();
        }

        // Info
        if ($UserDetailEditType == 'info') {
            $SaveData = [
                'status' => htmlspecialchars($status),
                'name' => htmlspecialchars($name),
                'avatar' => htmlspecialchars($avatar) ? htmlspecialchars($avatar) : $MyDetail['avatar'],
                'sex' => htmlspecialchars($sex)
            ];

            if ($request->getMethod() === 'POST') {
                $this->UserService->validateName($name);
                $len_status = mb_strlen($status);
                if (isset($status) && $len_status > 100) {
                    $error = 'Độ dài status không được vượt quá 100 ký tự, hiện tại: <b>' . $len_status . '</b>';
                }
                if (!in_array($sex, ['boy', 'girl'])) {
                    $sex = 'girl';
                }
                $intavt = intval($avatar);
                if (($avatar)) {
                    if ($intavt < 1 || $intavt > 29) {
                        $error = 'Ảnh đại diện được chọn không hợp lệ';
                    }
                }

                if (!$error) {
                    $this->UserModel->UserDetailEdit($MyDetail, $SaveData);
                    redirect('/user/' . $MyDetail['nick']);
                }
            }

            return array_merge($SaveData, [
                'error' => $error,
                'action' => 'info'
            ]);
        }

        // avatar, cover
        $AvatarCoverParttern = '/^https?:\/\/(i\.)?imgur\.com\/[a-zA-Z0-9]+(\.jpg|\.jpeg|\.png|\.gif)?$/';
        if ($UserDetailEditType == 'avatar') {
            $SaveData = [
                'avatar' => htmlspecialchars($avatar) ? htmlspecialchars($avatar) : $MyDetail['avatar'],
            ];
            if ($request->getMethod() === 'POST') {
                if (empty($avatar)) {
                    $error = 'Có lỗi xảy ra khi tải lên ảnh đại diện';
                }
                if (!preg_match($AvatarCoverParttern, $avatar)) {
                    $error = 'Ảnh đại diện được tải lên không hợp lệ';
                }
                if (!$error) {
                    $this->UserModel->UserDetailEdit($MyDetail, $SaveData);
                    redirect('/user/' . $MyDetail['nick']);
                }
            }
            return array_merge($SaveData, [
                'error' => $error,
                'action' => 'avatar'
            ]);
        } elseif ($UserDetailEditType == 'cover') {
            $SaveData = [
                'cover' => htmlspecialchars($cover) ? htmlspecialchars($cover) : $MyDetail['cover'],
            ];
            if ($request->getMethod() === 'POST') {
                if (empty($cover)) {
                    $error = 'Có lỗi xảy ra khi tải lên ảnh bìa';
                }
                if (!preg_match($AvatarCoverParttern, $cover)) {
                    $error = 'Ảnh bìa được tải lên không hợp lệ';
                }
                if (!$error) {
                    $this->UserModel->UserDetailEdit($MyDetail, $SaveData);
                    redirect('/user/' . $MyDetail['nick']);
                }
            }
            return array_merge($SaveData, [
                'error' => $error,
                'action' => 'cover'
            ]);
        }
        // password
        if ($UserDetailEditType == 'password') {
            $SaveData = [
                'pass' => e_pass($new_password)
            ];
            if ($request->getMethod() === 'POST') {
                $error = [];
                // check old password
                if (e_pass($old_password) != $MyDetail['pass']) {
                    $error[] = 'Mật khẩu cũ không đúng';
                }
                // check new password
                $check = $this->UserService->validatePassword($new_password);
                if ($check) {
                    $error[] = $check;
                }
                // check repeat password
                $check = $this->UserService->validatePasswordConfirmation($new_password, $re_password);
                if ($check) {
                    $error[] = $check;
                }
                if (!$error) {
                    $this->UserModel->UserDetailEdit($MyDetail, $SaveData);
                    $this->logout();
                } else {
                    $error = display_error($error);
                }
            }
            return array_merge($SaveData, [
                'error' => $error,
                'action' => 'password'
            ]);
        }

        // blocklist
        if ($UserDetailEditType == 'blocklist') {
            $SaveData = [
                'blocklist' => $MyDetail['blocklist']
            ];
            if ($request->getMethod() === 'POST') {
                $block = '';
                $postBlockList = $_POST['block'];
                foreach ($postBlockList as $value) {
                    $value = htmlspecialchars($value);
                    $dub = $this->UserModel->UserDetailWithFields('nick', $value);

                    if ($dub && $dub['id']) {
                        $block .= $value . '.';
                    }
                }
                $SaveData['blocklist'] = str_replace($block, '', $MyDetail['blocklist']);
                $this->UserModel->UserDetailEdit($MyDetail, $SaveData);
                redirect('/user/' . $MyDetail['nick'] . '/blocklist');
            }

            $UserList = [];
            $BlockList = explode('.', $SaveData['blocklist']);
            $BlockList = array_filter($BlockList, fn($value) => $value !== '');
            $CountBlocked = count($BlockList);
            foreach ($BlockList as $nick) {
                $nick = mb_strtolower($nick);
                $UserList[] = $this->UserModel->UserDetailWithFields('nick', $nick);
            }
            return [
                'blocklist' => $UserList,
                'count_blocked' => $CountBlocked,
                'error' => $error,
                'action' => 'blocklist'
            ];
        }
    }
}
