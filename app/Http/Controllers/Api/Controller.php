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
     * @param array   $data    返回数据
     * @param string  $message 返回消息
     * @param bool    $strict  是否连同响应头一起改为302
     *
     * @return \Dingo\Api\Http\Response
     */
    public function redirect($data, $message = 'Found', $strict = false)
    {
        // 判断是否修改响应头
        if ($strict) {
            $this->response->setStatusCode(302);
        }

        return $this->success($data, 302, $message);
    }

    /**
     * 返回成功响应
     *
     * 支持以下四种用法：
     * $this->success($data);                // 默认状态码为200，状态值为OK
     * $this->success($data, 200);           // 状态码必须是数字，否则按下一种情况处理
     * $this->success($data, '成功');        // 给定消息时默认状态码为200
     * $this->success($data, 302, '重定向'); // 指定状态码和状态值时需要按顺序传入
     *
     * @param array  $data          返回数据
     * @param mixed  $codeOrMessage 响应码或者返回消息
     * @param string $message       返回消息
     *
     * @return \Dingo\Api\Http\Response
     */
    public function success($data, $codeOrMessage = 200, $message = 'OK')
    {
        // 根据是否是字符串判断参数对应情况
        if (is_string($codeOrMessage)) {
            $code = 200;
            $message = $codeOrMessage;
        } else {
            $code = $codeOrMessage;
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
