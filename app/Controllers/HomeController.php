<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Controllers;

use System\Classes\Controller;
use System\Classes\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
    public function __construct(
        protected Category $CategoryModel,
        protected Post $PostModel,
        protected User $UserModel
    ) {
    }

    // trang chủ
    public function index(Request $request)
    {
        // tiêu đề trang
        $page_title = config('system.app.name');

        // lọc bài viết
        $isForum = false;
        $PostCount = $this->CategoryModel->ForumStats()['count_post'];
        $page_query = '?';
        # số bài viết có trong 1 trang
        $per = 10;
        $page = $request->getVar('page', 1);
        $page = htmlspecialchars($page);
        $page = intval($page);
        if (preg_match('/[a-zA-Z]|%/', $page) || $page < 1) {
            $page = 1;
        }
        # lọc theo chuyên mục
        $NewsBool = false;
        $category_id = $request->getVar('category', 0);
        $category_id = htmlspecialchars($category_id);
        $category_id = intval($category_id);
        $isInCategory = false;
        $CategoryName = null;
        $CategorySlug = null;
        $CanPostPublish = false;
        $CategoryDetail = $this->CategoryModel->CategoryDetail($category_id);
        if ($category_id >= 1 && $CategoryDetail && $CategoryDetail['id'] == $category_id) {
            $CategoryName = $CategoryDetail['name'];
            $CategorySlug = $CategoryDetail['slug'];
            $page_title = 'Chuyên mục: ' . $CategoryName;
            $page_query .= 'category=' . $category_id . '&';
            $PostCount = $this->CategoryModel->ForumStats('count_post_in_category', $category_id);
            $isForum = true;
            if ($CategoryDetail['id'] == env('NewsID')) {
                $NewsBool = true;
            } else {
                $isInCategory = true;
            }
            if ($request->user()->isLogin) {
                $MyDetail = $request->user()->user;
                $CanPostPublish = true;
                if ($CategoryDetail['id'] == env('NewsID')) {
                    if ($MyDetail['level'] < 120) {
                        $CanPostPublish = false;
                    }
                }
            }
        }

        # lọc theo trường
        $order_by = $request->getVar('order_by', 'update_time');
        $order_by = mb_strtolower($order_by);
        $order_by = htmlspecialchars($order_by);
        if (in_array($order_by, config('system.PostList.order_by'))) {
            $page_query .= 'order_by=' . $order_by . '&';
        } else {
            $order_by = 'update_time';
        }
        # sắp xếp bài viết theo trường đã lọc
        $sort = $request->getVar('sort', 'desc');
        $sort = mb_strtolower($sort);
        $sort = htmlspecialchars($sort);
        if (in_array($sort, config('system.PostList.sort'))) {
            $page_query .= 'sort=' . $sort . '&';
        } else {
            $sort = 'desc';
        }
        # thêm điều kiện cho Forum
        if ($page > 1 || $order_by != 'update_time' || $sort != 'desc') {
            $isForum = true;
            if ($page_title == env('APP_NAME') && $request->user()->isLogin) {
                $page_title = 'Diễn đàn';
            }
        }
        # thêm số trang
        if ($page >= 1) {
            $page_query .= 'page=';
        }

        $page_max = ceil($PostCount / $per);
        $start = ($page - 1) * $per;

        // lấy danh sách bài đăng theo bộ lọc và sắp xếp
        $getPostList = $this->PostModel->PostList($category_id, $per, $order_by, $sort, $start);
        $PostList = [];
        foreach ($getPostList as $PostDetail) {
            $author = mb_strtolower($PostDetail['author']);
            $PostDetail['UserDetail'] = $this->UserModel->UserDetailWithFields('nick', $author);
            $PostDetail['chapter'] = $this->CategoryModel->ForumStats('count_chapter_in_post', $PostDetail['id']);
            $PostDetail['comment'] = $this->CategoryModel->ForumStats('count_comment_in_post', $PostDetail['id']);

            $PostList[] = $PostDetail;
        }

        //$UpdateIfNotMD5 = $this->UserModel->UpdateIfNotMD5();

        // trả về template
        return view()->setTitle($page_title)->render('home', [
            'CategoryList' => $this->CategoryModel->CategoryList(20),
            'ForumStats' => $this->CategoryModel->ForumStats(),
            'PostCount' => $PostCount,
            'PostList' => $PostList,
            'PostListPaging' => paging($page_query, $page, $page_max),
            'PostListConfig' => [
                'allow_order_by' => config('system.PostList.order_by'),
                'allow_sort' => config('system.PostList.sort'),
                'order_by' => $order_by,
                'sort' => $sort
            ],
            'isForum' => $isForum,
            'NewsBool' => $NewsBool,
            'UserListOnline' => $this->UserModel->UserListOnline(),

            'CategoryName' => $CategoryName,
            'CategorySlug' => $CategorySlug,
            'isInCategory' => $isInCategory,
            'CanPostPublish' => $CanPostPublish
        ]);
    }

    // trang quản lý
    public function manager(Request $request)
    {
        // chuyển hướng nếu không phải Admin level 120
        $this->UserModel->isAdmin120redirect($request);
        return view()->setTitle('Bảng quản trị')->render('manager', []);
    }
}
