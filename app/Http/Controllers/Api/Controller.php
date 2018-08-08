<?php

namespace App\Http\Controllers\Api;

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
     * 如果需要修改响应头，则有以下两种用法
     * $this->redirect($data, true);
     * $this->redirect('重定向', $data, true);
     *
     * @param mixed  $arg1
     * @param array  $arg2
     * @param bool   $strict 是否连同响应头一起改为302
     *
     * @return \Dingo\Api\Http\Response
     */
    public function redirect($arg1, $arg2 = null, $strict = false)
    {
        if (is_string($arg1)) {
            $message = $arg1;
            $data = $arg2;
        } else {
            $message = 'Found';
            $data = $arg1;
            if ($arg2 !== null) {
                $strict = $arg2;
            }
        }
        // 判断是否修改响应头
        if ($strict) {
            $this->response->setStatusCode(302);
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
        ]);
    }

    /**
     * 返回错误响应
     *
     * @param string $message 错误消息
     * @param int    $code    错误码
     */
    public function error($message, $code = 500)
    {
        // 由于是抛出异常因此不需要return
        $this->response->error($message, $code);
    }

    public function errorResponse($statusCode, $message=null, $code=0)
    {
        throw new HttpException($statusCode, $message, null, [], $code);
    }
}
