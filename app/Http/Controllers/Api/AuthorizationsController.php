<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthorizationsController extends Controller
{

    /**
     * CAS 登录
     *
     * @return mixed
     */
    public function userAuthenticate()
    {
        /** 请求认证的 URL @var string $url */
        $url = config('eeyes.account.url') . 'oauth/authorize?' . http_build_query([
                'client_id' => config('eeyes.account.app.id'),
                'redirect_uri' => app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('api.users.authorization.callback'),
                'response_type' => 'code',
                'scope' => implode(' ',[
                    'info-username.read',
                    'info-user_id.read',
                    'info-name.read',
                    'info-email.read',
                ]),
            ]);

        /** 向前端发送 201 重定向 */
        return $this->success(201, '重定向以完成授权', [
            'url' => $url
        ]);
    }

    /**
     * CAS 登录回调方法
     *
     * @param Request $request
     */
    public function userCallback(Request $request)
    {
        if ($request->has('error')) {
            return $this->error(401, '认证失败');
        }
        try {
            $client = new Client;
            /** @var string $response 使用 Code 获取 Token */
            $response = $client->post(config('eeyes.account.url') . 'oauth/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => config('eeyes.account.app.id'),
                    'client_secret' => config('eeyes.account.app.secret'),
                    'redirect_uri' => app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('api.users.authorization.callback'),
                    'code' => $request->get('code'),
                ],
            ]);
            /** @var array $data 将获取到的 Token 数据转换为数组 */
            $data = json_decode((string)$response->getBody(),true);

            /** @var string $response 通过 Token 获取用户详细信息 */
            $response = $client->get(config('eeyes.account.url') . 'api/user', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $data['token_type'] . ' ' . $data['access_token'],
                ]
            ]);
            /** @var array $data 将获取到的用户数据转换为数组 */
            $data = json_decode((string)$response->getBody(),true);
            if (!$user = User::where('username', $data['username'])->first())
            {
                $user = User::create([
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => bcrypt('default_password'),
                ]);
            }

            /** @var string $token 通过 JWT 获取用于前后端交互的用户 Token 并返回 */
            $token = Auth::guard('api_user')->fromUser($user);

            return $this->success(201, '认证成功', [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => Auth::guard('api_user')->factory()->getTTL() * 60
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $request->toArray());
            return $this->error(401, '认证失败');
        }
    }

    /**
     * 社团登录方法
     *
     * @param Request $request
     */
    public function organizationAuthenticate(Request $request)
    {
        /** 验证所需字段 */
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        /** 验证数据 */
        if (!$token = Auth::guard('api_organization')->attempt($credentials)) {
            return $this->error(401, trans('auth.failed'));
        }

        /** 返回 Token */
        return $this->success(201, '认证成功', [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api_organization')->factory()->getTTL() * 60
        ]);
    }

    /**
     * 刷新用户 Token
     *
     * @return \Dingo\Api\Http\Response
     */
    public function refreshUserToken()
    {
        return $this->success(201, '刷新成功', [
            'access_token' => Auth::guard('api_user')->refresh(),
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api_user')->factory()->getTTL() * 60
        ]);
    }

    /**
     * 刷新社团用户 Token
     *
     * @return \Dingo\Api\Http\Response
     */
    public function refreshOrganizationToken()
    {
        return $this->success('刷新成功', [
            'access_token' => Auth::guard('api_organization')->refresh(),
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api_organization')->factory()->getTTL() * 60
        ]);
    }

    /**
     * 删除当前登录用户 Token
     *
     * @return \Dingo\Api\Http\Response
     */
    public function userLogout()
    {
        Auth::guard('api_user')->logout();
        return $this->success(204, '删除成功');
    }

    /**
     * 删除当前登录社团用户 Token
     *
     * @return \Dingo\Api\Http\Response
     */
    public function organizationLogout()
    {
        Auth::guard('api_organization')->logout();
        return $this->success(204, '删除成功');
    }
}
