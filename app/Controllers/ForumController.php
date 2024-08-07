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
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\User;
use App\Services\DocService;

class ForumController extends Controller
{
    public function __construct(
        // duyệt các lớp Model
        protected Category $CategoryModel,
        protected Post $PostModel,
        protected Chapter $ChapterModel,
        protected Comment $CommentModel,
        protected User $UserModel,

        // duyệt các lớp Service
        protected DocService $DocService,
    ) {
    }

    /* ===== TÌM KIẾM ===== */

    public function Search(Request $request)
    {
        $query = $request->getVar('q');
        $query = $this->DocService->TrimContent($query);
        $query = _e($query);
        $query = mb_strlen($query) > 0 ? $query : 'Empty';
        $search = $this->CategoryModel->ForumSearch($query);

        $SearchResultList = $search['result'];
        $SearchResultCount = $search['count'];
        # số bài viết có trong 1 trang
        $per = 15;
        $page = $request->getVar('page', 1);
        $page = htmlspecialchars($page);
        $page = intval($page);
        if (preg_match('/[a-zA-Z]|%/', $page) || $page < 1) {
            $page = 1;
        }
        # thêm số trang
        if ($page >= 1) {
            $page_query = '?page=';
        }

        $page_max = ceil($SearchResultCount / $per);
        $start = ($page - 1) * $per;
        $end = $start + $per;
        if ($end >= $SearchResultCount) {
            $end = $SearchResultCount;
        }

        $SearchResultList = array_slice($SearchResultList, $start, $per);
        $SearchPaging = paging("?q=$query&page=", $page, $page_max);

        return view()->setTitle('Tìm kiếm')->render('forum/search', [
            'SearchQuery' => $query,
            'SearchResultList' => $SearchResultList,
            'SearchResultCount' => $SearchResultCount,
            'SearchPaging' => $SearchPaging
        ]);
    }

    /* ===== BÀI VIẾT ==== */
    public function PostDetail($PostSlug, Request $request)
    {
        $MyDetail = [];
        $isAdminForum = false;
        $AdminActionResult = null;
        $isPersonCanAction = false;
        $isInLikeList = false;
        if ($request->user()->isLogin) {
            $MyDetail = $request->user()->user;
        }

        $error = null;
        $page_title = 'Diễn đàn';
        $pattern = '/^(?P<id>\d+)-(?P<slug>[a-zA-Z0-9\-_]+)$/';
        if (preg_match($pattern, $PostSlug, $matches)) {
            $id = intval($matches['id']);
            $PostDetail = $this->PostModel->PostDetail($id);
            if (!$PostDetail) {
                $error = 'Không tìm thấy bài viết';
            }
            $page_title = $PostDetail['title'];
            $page_description = $this->DocService->PageDescription($PostDetail['content']);
            $page_keyword = $this->DocService->PageKeyword($page_title);
            $PostDetail['url'] = url('/forum/') . $id . '-' . $PostDetail['slug'] . '.html';
            if (preg_match('/\[img\](.*?)\[\/img\]/', $PostDetail['content'], $matches)) {
                $PostDetail['thumbnail'] = $matches[1];
            } else {
                $PostDetail['thumbnail'] = 0;
            }
            $PostDetail['content'] = $this->DocService->bbcode($PostDetail['content']);
            $PostDetail['view'] = $this->PostModel->PostViewUpdate($PostDetail);

            $CategoryDetail = $this->CategoryModel->CategoryDetail($PostDetail['category']);
            $UserDetail = $this->UserModel->UserDetailWithFields('nick', $PostDetail['author']);
            $ChapterList = $this->ChapterModel->ChapterList($PostDetail['id']);
            $PostListSimilar = $this->PostModel->PostListSimilar($PostDetail['category'], $id);

            // Thống kê trong bài viết
            $ForumStats = [
                'count_post_in_category' => $this->CategoryModel->ForumStats('count_post_in_category', $PostDetail['category']),
                'count_chapter_in_post' => $this->CategoryModel->ForumStats('count_chapter_in_post', $PostDetail['id']),
                'count_comment_in_post' => $this->CategoryModel->ForumStats('count_comment_in_post', $PostDetail['id']),
            ];

            // Comments
            $CommentCount = $ForumStats['count_comment_in_post'];
            # số bài viết có trong 1 trang
            $per = 10;
            $page = $request->getVar('page', 1);
            $page = htmlspecialchars($page);
            $page = intval($page);
            if (preg_match('/[a-zA-Z]|%/', $page) || $page < 1) {
                $page = 1;
            }
            # thêm số trang
            if ($page >= 1) {
                $page_query = '?page=';
            }

            $page_max = ceil($CommentCount / $per);
            $start = ($page - 1) * $per;
            $end = $start + $per;
            if ($end >= $CommentCount) {
                $end = $CommentCount;
            }
            # lấy danh sách comment
            $getCommentList = $this->CommentModel->CommentList($PostDetail['id'], $start, $end);
            $CommentList = [];
            foreach ($getCommentList as $CommentDetail) {
                $author = mb_strtolower($CommentDetail['author']);
                $CommentDetail['UserDetail'] = $this->UserModel->UserDetailWithFields('nick', $author);
                $CommentDetail['comment'] = $this->DocService->bbcode($CommentDetail['comment']);

                $CommentList[] = $CommentDetail;
            }
            # gửi comment
            $isPersonCanComment = true;
            $ReasonCanNotComment = null;
            $errorComment = null;
            if ($request->user()->isLogin) {
                $UserDetailBlockList = $this->UserModel->UserDetailBlockList($UserDetail)['Get'];
                if ($PostDetail['blocked'] == 1) {
                    $isPersonCanComment = false;
                    $ReasonCanNotComment = 'Chủ đề thảo luận này đã đóng cửa bình luận!';
                }
                if (in_array($MyDetail['nick'], $UserDetailBlockList)) {
                    $isPersonCanComment = false;
                    $ReasonCanNotComment = 'Bạn không thể bình luận trong chủ đề mà có tác giả đang chặn bạn!';
                }
                if (
                    $MyDetail['nick'] == $UserDetail['nick']
                    || $MyDetail['level'] >= 120 && $MyDetail['level'] >= $UserDetail['level']
                ) {
                    $isPersonCanAction = true;
                }

                if ($isPersonCanComment) {
                    $inputContent = $request->postVar('content', '');
                    $inputContent = $this->DocService->TrimContent($inputContent);
                    $inputContent_len = mb_strlen($inputContent);
                    if ($request->getMethod() === 'POST') {
                        // reset token
                        $token = $request->postVar('csrf_token', '');
                        $checktoken = isCSRFTokenValid($token);
                        if ($checktoken) {
                            $error[] = 'Invalid token';
                        }
                        unsetCSRFToken();
                        generateCSRFToken();
                        if (empty($inputContent)) {
                            $errorComment = 'Vui lòng nhập nội dung bình luận';
                        }
                        if ($inputContent_len < 5 || $inputContent_len > 1200) {
                            $errorComment = 'Nội dung bình luận không hợp lệ';
                        }
                        if (!$error) {
                            $this->CommentModel->CommentSend($PostDetail, $MyDetail['nick'], $inputContent);
                            redirect('/forum/' . $PostDetail['id'] . '-' . $PostDetail['slug'] . '.html');
                        }
                    }
                }
            }

            // thao tác với update data
            $mod = $request->getVar('mod', '');
            $PostLikeList = $this->PostModel->PostLikeList($id);
            if ($mod == 'like') {
                $PostLikeSave = $this->PostModel->PostLikeSave($id, $MyDetail['nick'], 'save');
                if ($PostLikeSave) {
                    redirect('?page=1');
                }
            }
            if ($request->user()->isLogin) {
                $isInLikeList = $this->PostModel->PostLikeSave($id, $MyDetail['nick'], 'check');
            }
            # lấy danh sách người đã like
            $PostLikeListDisplay = '';
            $PostLikeListCount = min(3, $this->PostModel->PostLikeList($id, 'count'));
            $liked_count = 0;
            foreach (array_slice($PostLikeList, 0, $PostLikeListCount) as $index => $pliked) {
                if ($pliked['id']) {
                    $liked_count++;
                    $PostLikeListDisplay .= RoleColor($pliked);
                    if ($index < $PostLikeListCount - 1) {
                        $PostLikeListDisplay .= ', ';
                    }
                }
            }
            $total_likes = count($PostLikeList);
            if ($total_likes > 4) {
                $additional_likes = $total_likes - 4;
                $PostLikeListDisplay .= ' và ' . $additional_likes . ' người khác';
            }
            if ($liked_count > 0) {
                $PostLikeListDisplay = '<div class="likelist">' . $PostLikeListDisplay . ' đã thích bài viết này</div>';
            }
            # đóng cửa, ghim bài
            $ActionLock = null;
            $ActionLockName = null;
            $ActionPin = null;
            $ActionPinName = null;
            if ($PostDetail['blocked'] == 1) {
                $ActionLock = 'unlock';
                $ActionLockName = '<i class="fa fa-unlock" aria-hidden="true"></i> Mở thảo luận';
            } else if ($PostDetail['blocked'] == 0) {
                $ActionLock = 'lock';
                $ActionLockName = '<i class="fa fa-lock" aria-hidden="true"></i> Đóng thảo luận';
            }
            if ($PostDetail['sticked'] == 1) {
                $ActionPin = 'unpin';
                $ActionPinName = '<i class="fa fa-file-text" aria-hidden="true"></i> Gỡ ghim';
            } else if ($PostDetail['sticked'] == 0) {
                $ActionPin = 'pin';
                $ActionPinName = '<i class="fa fa-thumb-tack" aria-hidden="true"></i> Ghim chủ đề';
            }
            if ($request->user()->isLogin) {
                if ($MyDetail['level'] >= 120 && $MyDetail['level'] >= $UserDetail['level']) {
                    $isAdminForum = true;
                    if ($mod == 'unlock' && $PostDetail['blocked'] == 1) {
                        $this->PostModel->PostSaveOne($id, 'blocked', 0);
                        $AdminActionResult = 'Mở cửa chủ đề thành công';
                    } elseif ($mod == 'lock' && $PostDetail['blocked'] == 0) {
                        $this->PostModel->PostSaveOne($id, 'blocked', 1);
                        $AdminActionResult = 'Đóng cửa chủ đề thành công';
                    } elseif ($mod == 'unpin' && $PostDetail['sticked'] == 1) {
                        $this->PostModel->PostSaveOne($id, 'sticked', 0);
                        $AdminActionResult = 'Gỡ ghim chủ đề thành công';
                    } elseif ($mod == 'pin' && $PostDetail['sticked'] == 0) {
                        $this->PostModel->PostSaveOne($id, 'sticked', 1);
                        $AdminActionResult = 'Ghim chủ đề thành công';
                    }
                }
            }
        } else {
            $error = 'Thao tác không hợp lệ';
        }

        if ($error) {
            redirect('/404');
        }

        // return vào lớp view
        return view()
            ->setTitle($page_title)
            ->setDescription($page_description)
            ->setKeyword($page_keyword)
            ->render('forum/post_detail', [
                'CategoryDetail' => $CategoryDetail,
                'PostDetail' => $PostDetail,
                'UserDetail' => $UserDetail,
                'ForumStats' => $ForumStats,
                'ChapterList' => $ChapterList,
                'PostListSimilar' => $PostListSimilar,

                'CommentList' => $CommentList,
                'CommentListPaging' => paging($page_query, $page, $page_max),
                'isPersonCanComment' => $isPersonCanComment,
                'ReasonCanNotComment' => $ReasonCanNotComment,

                'MyDetail' => $MyDetail,
                'isInLikeList' => $isInLikeList,
                'PostLikeList' => $PostLikeList,
                'PostLikeListDisplay' => $PostLikeListDisplay,

                'isAdminForum' => $isAdminForum,
                'AdminActionResult' => $AdminActionResult,
                'isPersonCanAction' => $isPersonCanAction,
                'errorComment' => $errorComment,

                'ActionLock' => $ActionLock,
                'ActionLockName' => $ActionLockName,
                'ActionPin' => $ActionPin,
                'ActionPinName' => $ActionPinName
            ]);
    }

    public function PostPublish(Request $request)
    {
        $HaveCategoryDetail = false;
        $error = [];
        // chuyển hướng nếu không phải là Admin level 120
        $this->UserModel->isAdmin120redirect($request);
        $MyDetail = $request->user()->user;
        $page_title = 'Đăng bài mới';

        // lấy danh sách chuyên mục
        $CategoryList = $this->CategoryModel->CategoryList(20);

        // xử lý dữ liệu
        $inputTitle = $request->postVar('title', '');
        $inputTitle = $this->DocService->TrimContent($inputTitle);
        $inputContent = $request->postVar('content', '');
        $inputContent = $this->DocService->TrimContent($inputContent);
        $inputCategory = $request->postVar('category', '');
        $inputCategory = $this->DocService->TrimContent($inputCategory);

        if ($request->getMethod() === 'POST') {
            // reset token
            $token = $request->postVar('csrf_token', '');
            $checktoken = isCSRFTokenValid($token);
            if ($checktoken) {
                $error[] = 'Invalid token';
            }
            unsetCSRFToken();
            generateCSRFToken();

            // kiểm tra xem chuyên mục có tồn tại không
            $CategoryCheckExists = $this->CategoryModel->ForumCheckExists('category', 'id', $inputCategory);
            if (!$CategoryCheckExists) {
                $error[] = 'Chuyên mục không tồn tại';
            }

            if (empty($inputTitle) || empty($inputContent)) {
                $error[] = 'Tiêu đề và nội dung không được để trống';
            }
            $inputTitle_len = mb_strlen($inputTitle);
            if ($inputTitle_len < 5 || $inputTitle_len > 100) {
                $error[] = 'Độ dài tiêu đề phải từ 5 đến 100 ký tự';
            }
            $inputContent_len = mb_strlen($inputContent);
            if ($inputContent_len < 5 || $inputContent_len > 3000) {
                $error[] = 'Nội dung bài viết không hợp lệ';
            }
            if (!$error) {
                $inputSlug = $this->DocService->slug($inputTitle);
                $PostCheckExists = $this->CategoryModel->ForumCheckExists('blog', 'slug', $inputSlug);
                if ($PostCheckExists) {
                    $error[] = 'Bài viết này đã tồn tại, nếu bạn muốn tạo 1 bài viết với chủ đề thảo luận tương tự, vui lòng thay đổi tiêu đề';
                }
            }
            if (!$error) {
                $PostID = $this->PostModel->PostPublish($inputCategory, $inputTitle, $inputSlug, $inputContent, $MyDetail['nick']);
                redirect('/forum/' . $PostID . '-' . $inputSlug . '.html');
            } else {
                $error = display_error($error);
            }
        }

        return view()->setTitle($page_title)->render('forum/post_publish', [
            'HaveCategoryDetail' => $HaveCategoryDetail,
            'CategoryList' => $CategoryList,

            'error' => $error,
            'inputTitle' => _e($inputTitle),
            'inputContent' => _e($inputContent),
            'inputCategory' => _e($inputCategory)
        ]);
    }

    public function PostPublishOne($CategorySlug, Request $request)
    {
        $HaveCategoryDetail = true;
        $error = [];
        // chuyển hướng nếu không phải thành viên
        $this->UserModel->isLoginredirect($request);
        $MyDetail = $request->user()->user;

        // kiểm tra xem chuyên mục có tồn tại không
        $CategoryCheckExists = $this->CategoryModel->ForumCheckExists('category', 'slug', $CategorySlug);
        if (!$CategoryCheckExists) {
            redirect('/');
        }

        // lấy thông tin chuyên mục đang truy cập theo cột slug
        $CategoryDetail = $this->CategoryModel->ForumDetailWithFields('category', 'slug', $CategorySlug);
        $page_title = 'Đăng bài mới - ' . $CategoryDetail['name'];

        // xử lý dữ liệu
        $inputTitle = $request->postVar('title', '');
        $inputTitle = $this->DocService->TrimContent($inputTitle);
        $inputContent = $request->postVar('content', '');
        $inputContent = $this->DocService->TrimContent($inputContent);

        if ($request->getMethod() === 'POST') {
            // reset token
            $token = $request->postVar('csrf_token', '');
            $checktoken = isCSRFTokenValid($token);
            if ($checktoken) {
                $error[] = 'Invalid token';
            }
            unsetCSRFToken();
            generateCSRFToken();

            if (empty($inputTitle) || empty($inputContent)) {
                $error[] = 'Tiêu đề và nội dung không được để trống';
            }
            $inputTitle_len = mb_strlen($inputTitle);
            if ($inputTitle_len < 5 || $inputTitle_len > 100) {
                $error[] = 'Độ dài tiêu đề phải từ 5 đến 100 ký tự';
            }
            $inputContent_len = mb_strlen($inputContent);
            if ($inputContent_len < 5 || $inputContent_len > 3000) {
                $error[] = 'Nội dung bài viết không hợp lệ';
            }
            if (!$error) {
                $inputSlug = $this->DocService->slug($inputTitle);
                $PostCheckExists = $this->CategoryModel->ForumCheckExists('blog', 'slug', $inputSlug);
                if ($PostCheckExists) {
                    $error[] = 'Bài viết này đã tồn tại, nếu bạn muốn tạo 1 bài viết với chủ đề thảo luận tương tự, vui lòng thay đổi tiêu đề';
                }
            }
            if (!$error) {
                $PostID = $this->PostModel->PostPublish($CategoryDetail['id'], $inputTitle, $inputSlug, $inputContent, $MyDetail['nick']);
                redirect('/forum/' . $PostID . '-' . $inputSlug . '.html');
            } else {
                $error = display_error($error);
            }
        }

        return view()->setTitle($page_title)->render('forum/post_publish', [
            'HaveCategoryDetail' => $HaveCategoryDetail,
            'CategoryDetail' => $CategoryDetail,

            'error' => $error,
            'inputTitle' => _e($inputTitle),
            'inputContent' => _e($inputContent)
        ]);
    }

    public function ForumEdit($action, $id, Request $request)
    {
        $error = [];
        $ChapterDetail = [];
        $PostDetail = [];
        if ($action == 'chapter') {
            // kiểm tra xem chương có tồn tại không
            $ChapterCheckExists = $this->CategoryModel->ForumCheckExists('chap', 'id', $id);
            if (!$ChapterCheckExists) {
                redirect('/');
            }
            // lấy thông tin chương
            $ChapterDetail = $this->CategoryModel->ForumDetailWithFields('chap', 'id', $id);
            $page_title = 'Sửa chương: ' . $ChapterDetail['title'];
            // lấy thông tin bài viết
            $PostDetail = $this->PostModel->PostDetail($ChapterDetail['box']);

            // nạp dữ liệu vào input
            $DefaultInputTitle = $ChapterDetail['title'];
            $DefaultInputContent = $ChapterDetail['content'];
            $DefaultInputCategory = $PostDetail['category'];
        } else {
            // kiểm tra xem bài viết có tồn tại không
            $PostCheckExists = $this->CategoryModel->ForumCheckExists('blog', 'id', $id);
            if (!$PostCheckExists) {
                redirect('/');
            }
            // lấy thông tin bài viết
            $PostDetail = $this->PostModel->PostDetail($id);
            $page_title = 'Sửa bài viết: ' . $PostDetail['title'];

            // nạp dữ liệu vào input
            $DefaultInputTitle = $PostDetail['title'];
            $DefaultInputContent = $PostDetail['content'];
            $DefaultInputCategory = $PostDetail['category'];
        }

        // kiểm tra xem có phải tác giả hoặc admin level 120 không
        $this->UserModel->isLoginredirect($request);
        $MyDetail = $request->user()->user;
        $UserDetail = $this->UserModel->UserDetailWithFields('nick', $PostDetail['author']); # thông tin tác giả
        if (!($MyDetail['nick'] == $PostDetail['author'] || $MyDetail['level'] >= 120 && $MyDetail['level'] >= $UserDetail['level'])) {
            redirect('/');
        }

        // lấy dữ liệu bảng category
        $CategoryList = $this->CategoryModel->CategoryList(20);
        $CategoryDetail = $this->CategoryModel->CategoryDetail($PostDetail['category']);

        // xử lý dữ liệu
        $inputTitle = $request->postVar('title', $DefaultInputTitle);
        $inputTitle = $this->DocService->TrimContent($inputTitle);
        $inputContent = $request->postVar('content', $DefaultInputContent);
        $inputContent = $this->DocService->TrimContent($inputContent);
        $inputCategory = $request->postVar('category', $DefaultInputCategory);
        $inputCategory = $this->DocService->TrimContent($inputCategory);

        if ($request->getMethod() === 'POST') {
            // reset token
            $token = $request->postVar('csrf_token', '');
            $checktoken = isCSRFTokenValid($token);
            if ($checktoken) {
                $error[] = 'Invalid token';
            }
            unsetCSRFToken();
            generateCSRFToken();

            // kiểm tra xem chuyên mục có tồn tại không
            $CategoryCheckExists = $this->CategoryModel->ForumCheckExists('category', 'id', $inputCategory);
            if (!$CategoryCheckExists) {
                $error[] = 'Chuyên mục không tồn tại';
            }

            if (empty($inputTitle) || empty($inputContent)) {
                $error[] = 'Tiêu đề và nội dung không được để trống';
            }
            $inputTitle_len = mb_strlen($inputTitle);
            if ($inputTitle_len < 5 || $inputTitle_len > 100) {
                $error[] = 'Độ dài tiêu đề phải từ 5 đến 100 ký tự';
            }
            $inputContent_len = mb_strlen($inputContent);
            if ($inputContent_len < 5 || $inputContent_len > 3000) {
                $error[] = 'Nội dung bài viết không hợp lệ';
            }
            if (!$error) {
                $inputSlug = $this->DocService->slug($inputTitle);
                $PostCheckExists = $this->CategoryModel->ForumCheckExists('blog', 'slug', $inputSlug);
                if ($inputSlug != $PostDetail['slug'] && $PostCheckExists) {
                    $error[] = 'Bài viết này đã tồn tại, nếu bạn muốn tạo 1 bài viết với chủ đề thảo luận tương tự, vui lòng thay đổi tiêu đề';
                }
            }
            if (!$error) {
                if ($action == 'chapter') {
                    $this->ChapterModel->ChapterEdit($id, [
                        'title' => $inputTitle,
                        'slug' => $inputSlug,
                        'content' => $inputContent
                    ]);
                    redirect('/view-chap/' . $id . '-' . $inputSlug . '.html');
                } else {
                    $this->PostModel->PostEdit($id, [
                        'title' => $inputTitle,
                        'slug' => $inputSlug,
                        'content' => $inputContent,
                        'category' => $inputCategory
                    ]);
                    redirect('/forum/' . $id . '-' . $inputSlug . '.html');
                }
            } else {
                $error = display_error($error);
            }
        }

        return view()->setTitle($page_title)->render('forum/forum_edit', [
            'ChapterDetail' => $ChapterDetail,
            'PostDetail' => $PostDetail,
            'CategoryList' => $CategoryList,
            'CategoryDetail' => $CategoryDetail,

            'action' => $action,
            'error' => $error,
            'inputTitle' => $inputTitle,
            'inputContent' => $inputContent,
            'inputCategory' => _e($inputCategory)
        ]);
    }

    public function ForumDelete($action, $id, Request $request)
    {
        $error = [];
        $ChapterDetail = [];
        $PostDetail = [];
        if ($action == 'chapter') {
            // kiểm tra xem chương có tồn tại không
            $ChapterCheckExists = $this->CategoryModel->ForumCheckExists('chap', 'id', $id);
            if (!$ChapterCheckExists) {
                redirect('/');
            }
            // lấy thông tin chương
            $ChapterDetail = $this->CategoryModel->ForumDetailWithFields('chap', 'id', $id);
            $page_title = 'Xóa chương: ' . $ChapterDetail['title'];
            // lấy thông tin bài viết
            $PostDetail = $this->PostModel->PostDetail($ChapterDetail['box']);
        } else {
            // kiểm tra xem bài viết có tồn tại không
            $PostCheckExists = $this->CategoryModel->ForumCheckExists('blog', 'id', $id);
            if (!$PostCheckExists) {
                redirect('/');
            }
            // lấy thông tin bài viết
            $PostDetail = $this->PostModel->PostDetail($id);
            $page_title = 'Xóa bài viết: ' . $PostDetail['title'];
        }

        // kiểm tra xem có phải tác giả hoặc admin level 120 không
        $this->UserModel->isLoginredirect($request);
        $MyDetail = $request->user()->user;
        $UserDetail = $this->UserModel->UserDetailWithFields('nick', $PostDetail['author']); # thông tin tác giả
        if (!($MyDetail['nick'] == $PostDetail['author'] || $MyDetail['level'] >= 120 && $MyDetail['level'] >= $UserDetail['level'])) {
            redirect('/');
        }

        // lấy dữ liệu bảng category
        $CategoryList = $this->CategoryModel->CategoryList(20);
        $CategoryDetail = $this->CategoryModel->CategoryDetail($PostDetail['category']);

        if ($request->getMethod() === 'POST') {
            // reset token
            $token = $request->postVar('csrf_token', '');
            $checktoken = isCSRFTokenValid($token);
            if ($checktoken) {
                $error[] = 'Invalid token';
            }
            unsetCSRFToken();
            generateCSRFToken();

            if (!$error) {
                if ($action == 'chapter') {
                    $this->ChapterModel->ChapterDelete($id);
                } else {
                    $this->PostModel->PostDelete($id);
                }
                redirect('/forum');
            } else {
                $error = display_error($error);
            }
        }

        return view()->setTitle($page_title)->render('forum/forum_delete', [
            'ChapterDetail' => $ChapterDetail,
            'PostDetail' => $PostDetail,
            'CategoryList' => $CategoryList,
            'CategoryDetail' => $CategoryDetail,

            'action' => $action,
            'error' => $error
        ]);
    }

    /* ===== CHAPTER ===== */

    public function ChapterPublish($id, Request $request)
    {
        /**
         * id: khóa id của post thuộc bảng `blog`
         */
        $error = [];
        // kiểm tra xem bài viết có tồn tại không
        $PostCheckExists = $this->CategoryModel->ForumCheckExists('blog', 'id', $id);
        if (!$PostCheckExists) {
            redirect('/');
        }
        // lấy thông tin bài viết
        $PostDetail = $this->PostModel->PostDetail($id);
        $page_title = 'Thêm chương vào bài viết: ' . $PostDetail['title'];
        // lấy dữ liệu bảng category
        $CategoryDetail = $this->CategoryModel->CategoryDetail($PostDetail['category']);
        // kiểm tra xem có phải tác giả hoặc admin level 120 không
        $this->UserModel->isLoginredirect($request);
        $MyDetail = $request->user()->user;
        $UserDetail = $this->UserModel->UserDetailWithFields('nick', $PostDetail['author']); # thông tin tác giả
        if (!($MyDetail['nick'] == $PostDetail['author'] || $MyDetail['level'] >= 120 && $MyDetail['level'] >= $UserDetail['level'])) {
            redirect('/');
        }

        // xử lý dữ liệu
        $inputTitle = $request->postVar('title', '');
        $inputTitle = $this->DocService->TrimContent($inputTitle);
        $inputContent = $request->postVar('content', '');
        $inputContent = $this->DocService->TrimContent($inputContent);

        if ($request->getMethod() === 'POST') {
            // reset token
            $token = $request->postVar('csrf_token', '');
            $checktoken = isCSRFTokenValid($token);
            if ($checktoken) {
                $error[] = 'Invalid token';
            }
            unsetCSRFToken();
            generateCSRFToken();

            if (empty($inputTitle) || empty($inputContent)) {
                $error[] = 'Tiêu đề và nội dung không được để trống';
            }
            $inputTitle_len = mb_strlen($inputTitle);
            if ($inputTitle_len < 5 || $inputTitle_len > 100) {
                $error[] = 'Độ dài tiêu đề phải từ 5 đến 100 ký tự';
            }
            $inputContent_len = mb_strlen($inputContent);
            if ($inputContent_len < 5 || $inputContent_len > 3000) {
                $error[] = 'Nội dung bài viết không hợp lệ';
            }
            if (!$error) {
                $inputSlug = $this->DocService->slug($inputTitle);
                $PostCheckExists = $this->CategoryModel->ForumCheckExists('chap', 'slug', $inputSlug);
                if ($PostCheckExists) {
                    $error[] = 'Bài viết này đã tồn tại, nếu bạn muốn tạo 1 bài viết với chủ đề thảo luận tương tự, vui lòng thay đổi tiêu đề';
                }
            }
            if (!$error) {
                $ChapterID = $this->ChapterModel->ChapterPublish($PostDetail['id'], $inputTitle, $inputSlug, $inputContent, $MyDetail['nick']);
                redirect('/view-chap/' . $ChapterID . '-' . $inputSlug . '.html');
            } else {
                $error = display_error($error);
            }
        }

        return view()->setTitle($page_title)->render('forum/chapter_publish', [
            'PostDetail' => $PostDetail,
            'CategoryDetail' => $CategoryDetail,

            'inputTitle' => $inputTitle,
            'inputContent' => $inputContent,

            'error' => $error
        ]);
    }

    public function ChapterDetail($ChapterSlug, Request $request)
    {
        $MyDetail = [];
        $isPersonCanAction = false;

        $error = null;
        $pattern = '/^(?P<id>\d+)-(?P<slug>[a-zA-Z0-9\-_]+)$/';
        if (preg_match($pattern, $ChapterSlug, $matches)) {
            $id = intval($matches['id']);
            $ChapterDetail = $this->CategoryModel->ForumDetailWithFields('chap', 'id', $id);
            if (!$ChapterDetail) {
                $error = 'Không tìm thấy bài viết';
            }
            $page_title = $ChapterDetail['title'];
            $page_description = $this->DocService->PageDescription($ChapterDetail['content']);
            $page_keyword = $this->DocService->PageKeyword($page_title);

            // lấy thông tin bài viết
            $PostDetail = $this->PostModel->PostDetail($ChapterDetail['box']);
            $UserDetail = $this->UserModel->UserDetailWithFields('nick', $PostDetail['author']);
            // lấy thông tin chuyên mục
            $CategoryDetail = $this->CategoryModel->CategoryDetail($PostDetail['category']);

            // lượt xem
            $PostDetail['view'] = $this->PostModel->PostViewUpdate($PostDetail);

            $ChapterDetail['url'] = url('/view-chap/') . $id . '-' . $ChapterDetail['slug'] . '.html';
            $ChapterDetail['content'] = $this->DocService->bbcode($ChapterDetail['content']);
            $PostListSimilar = $this->PostModel->PostListSimilar($PostDetail['category'], $PostDetail['id']);
            $ChapterList = $this->ChapterModel->ChapterList($PostDetail['id']);

            // kiểm tra xem có phải tác giả hoặc admin level 120 không
            if ($request->user()->isLogin) {
                $MyDetail = $request->user()->user;
                $UserDetail = $this->UserModel->UserDetailWithFields('nick', $PostDetail['author']); # thông tin tác giả
                if ($MyDetail['nick'] == $PostDetail['author'] || $MyDetail['level'] >= 120 && $MyDetail['level'] >= $UserDetail['level']) {
                    $isPersonCanAction = true;
                }
            }

            // Thống kê trong bài viết
            $ForumStats = [
                'count_post_in_category' => $this->CategoryModel->ForumStats('count_post_in_category', $PostDetail['category']),
                'count_chapter_in_post' => $this->CategoryModel->ForumStats('count_chapter_in_post', $PostDetail['id']),
                'count_comment_in_post' => $this->CategoryModel->ForumStats('count_comment_in_post', $PostDetail['id']),
            ];
        } else {
            $error = 'Thao tác không hợp lệ';
        }
        if ($error) {
            redirect('/404');
        }

        return view()
            ->setTitle($page_title)
            ->setDescription($page_description)
            ->setKeyword($page_keyword)
            ->render('forum/chapter_detail', [
                'CategoryDetail' => $CategoryDetail,
                'PostDetail' => $PostDetail,
                'ChapterDetail' => $ChapterDetail,
                'UserDetail' => $UserDetail,
                'PostListSimilar' => $PostListSimilar,
                'ForumStats' => $ForumStats,
                'ChapterList' => $ChapterList,

                'isPersonCanAction' => $isPersonCanAction
            ]);
    }

    /* ===== CHUYÊN MỤC ===== */
    public function CategoryPublish(Request $request)
    {
        // chuyển hướng nếu không phải Admin level 120
        $this->UserModel->isAdmin120redirect($request);

        // đếm số lượng chuyên mục hiện có
        $CategoryCount = $this->CategoryModel->ForumStats()['count_category'];

        // thao tác dữ liệu
        $CategoryName = $request->postVar('name', '');
        $CategoryName = $this->DocService->TrimContent($CategoryName);
        $CategoryContent = $request->postVar('content', '');
        $CategoryContent = $this->DocService->TrimContent($CategoryContent);
        $CategoryKeyword = $request->postVar('keyword', '');
        $CategoryKeyword = $this->DocService->TrimContent($CategoryKeyword);
        $CategoryID = $request->postVar('category_id', '');
        $SubmitButton = $request->postVar('submit', 'create');
        $error = [];
        if ($request->getMethod() === 'POST') {
            // reset token
            $token = $request->postVar('csrf_token', '');
            $checktoken = isCSRFTokenValid($token);
            if ($checktoken) {
                $error[] = 'Invalid token';
            }
            unsetCSRFToken();
            generateCSRFToken();

            // tạo chuyên mục
            if ($SubmitButton == 'create') {
                if (empty($CategoryName) || empty($CategoryContent) || empty($CategoryKeyword)) {
                    $error[] = 'Vui lòng điền đầy đủ thông tin cho chuyên mục';
                }
                $CategoryName_len = mb_strlen($CategoryName);
                if ($CategoryName_len < 3 || $CategoryName_len > 70) {
                    $error[] = 'Độ dài tiêu đề chuyên mục không hợp lệ (min. 3, max. 70)';
                }
                $CategorySlug = $this->DocService->slug($CategoryName);
                if ($this->CategoryModel->ForumCheckExists('category', 'slug', $CategorySlug)) {
                    $error[] = 'Chuyên mục này đã tồn tại, nếu bạn muốn tạo 1 chuyên mục có các chủ đề tương tự, xin hãy đổi tên khác';
                }
                if (!$error) {
                    $this->CategoryModel->CategoryPublish($CategoryName, $CategorySlug, $CategoryContent, $CategoryKeyword);
                    redirect('/forum/category');
                }
            } elseif ($SubmitButton == 'delete') {
                if (empty($CategoryID)) {
                    $error[] = 'Vui lòng chọn chuyên mục muốn xóa';
                }
                if (!$error) {
                    $this->CategoryModel->CategoryDelete($CategoryID);
                    redirect('/forum/category');
                }
            }
        }
        if ($error) {
            $error = display_error($error);
        }

        return view()->setTitle('Quản lý: Chuyên mục')->render('forum/category_publish', [
            'CategoryList' => $this->CategoryModel->CategoryList(),
            'CategoryCount' => $CategoryCount,
            'error' => $error
        ]);
    }

    /* ===== SEO SITEMAP ===== */
    // seo sitemap
    public function sitemap(Request $request)
    {
        $CategoryCount = $this->CategoryModel->ForumStats()['count_category'];
        $PostCount = $this->CategoryModel->ForumStats()['count_post'];
        $ChapterCount = $this->CategoryModel->ForumStats()['count_chapter'];

        $CategoryList = $this->CategoryModel->CategoryList();
        $PostList = $this->PostModel->PostList(0, $PostCount, 'id', 'asc', 0);
        $ChapterList = $this->ChapterModel->ChapterList(0);

        return view()->render('forum/_seo.sitemap', [
            'CategoryCount' => $CategoryCount,
            'PostCount' => $PostCount,
            'ChapterCount' => $ChapterCount,

            'CategoryList' => $CategoryList,
            'PostList' => $PostList,
            'ChapterList' => $ChapterList
        ]);
    }
}