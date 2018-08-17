<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller as BaseController;
use Dingo\Api\Routing\Helpers;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{
    use Helpers;

    /**
     * 返回重定向响应
     *
     * 支持以下两种用法
     * $this->redirect($data);
     * $this->redirect('重定向', $data);
     *
     * @param mixed  $arg1
     * @param mixed  $arg2
     *
     * @return \Dingo\Api\Http\Response
     */
    public function redirect($arg1, $arg2 = null)
    {
        if (is_string($arg1)) {
            $message = $arg1;
            $data = $arg2;
        } else {
            $message = 'Found';
            $data = $arg1;
        }

        return $this->success(302, $message, $data);
    }

    /**
     * 返回成功响应
     *
     * 支持以下三种用法：
     * $this->success($data);                // 默认状态码为200，状态值为OK
     * $this->success('成功', $data);        // 默认状态码为200，传入状态值和数据
     * $this->success(302, '重定向', $data); // 指定状态码和状态值，传入数据
     *
     * @param mixed $arg1
     * @param mixed $arg2
     * @param mixed $arg3
     *
     * @return \Dingo\Api\Http\Response
     */
    public function success($arg1, $arg2 = null, $arg3 = null)
    {
        // 根据类型确定各参数含义
        if (is_array($arg1)) {
            $code = 200;
            $message = 'OK';
            $data = $arg1;
        } else if (is_string($arg1)) {
            $code = 200;
            $message = $arg1;
            $data = $arg2;
        } else {
            $code = $arg1;
            $message = $arg2;
            $data = $arg3;
        }

        return $this->response->array([
            'status_code' => $code,
            'message'     => $message,
            'data'        => $data
        ])->setStatusCode($code);
    }

    /**
     * 返回错误响应
     *
     * 支持以下两种用法
     * $this->error('服务器错误');
     * $this->error(500, '服务器错误');
     *
     * @param mixed $arg1
     * @param mixed $arg2
     */
    public function error($arg1, $arg2 = null)
    {
        if (is_int($arg1)) {
            $code = $arg1;
            $message = $arg2;
        } else {
            $code = 500;
            $message = $arg1;
        }
        // 由于是抛出异常因此不需要return
        $this->response->error($message, $code);
    }

    public function errorResponse($statusCode, $message=null, $code=0)
    {
        throw new HttpException($statusCode, $message, null, [], $code);
    }

    /**
     * 方便获取当前用户
     *
     * @return mixed User|Organization
     */
    public function getUserOrActiveOrganization()
    {
        if (Auth::guard('api_user')->check()) {
            return Auth::guard('api_user')->user();
        } elseif (Auth::guard('api_organization')->check() && Auth::guard('api_organization')->user()->active) {
            return Auth::guard('api_organization')->user();
        } else {
            $this->error(403,'尚未登录或权限不足。');
        }
    }

    /**
     * @return mixed
     */
    public function getUserOrOrganization()
    {
        if (Auth::guard('api_user')->check()) {
            return Auth::guard('api_user')->user();
        } elseif (Auth::guard('api_organization')->check()) {
            return Auth::guard('api_organization')->user();
        } else {
            $this->error(403,'尚未登录或权限不足。');
        }
    }
}
