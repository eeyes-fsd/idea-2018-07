<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\ContextFactory;

class AuthorizationsController extends Controller
{
    public function organizationAuthenticate(Request $request)
    {
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!$token = Auth::guard('api_organization')->attempt($credentials)) {
            return $this->response->errorUnauthorized(trans('auth.failed'));
        }

        return $this->response->array([
            'status_code' => '200',
            'message' => '认证成功',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => Auth::guard('api_organization')->factory()->getTTL() * 60
            ]
        ]);
    }

    public function userAuthenticate()
    {
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

        return $this->response->array([
            'status_code' => '302',
            'data' => $url,
            'message' => '重定向以完成授权'
        ]);
    }

    public function userCallback(Request $request)
    {
        if ($request->has('error')) {
            return $this->response->error('认证失败',401);
        }
        try {
            $client = new Client;
            $response = $client->post(config('eeyes.account.url') . 'oauth/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => config('eeyes.account.app.id'),
                    'client_secret' => config('eeyes.account.app.secret'),
                    'redirect_uri' => app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('api.users.authorization.callback'),
                    'code' => $request->get('code'),
                ],
            ]);
            $data = json_decode((string)$response->getBody(),true);
            $response = $client->get(config('eeyes.account.url') . 'api/user', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $data['token_type'] . ' ' . $data['access_token'],
                ]
            ]);
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
            $token = Auth::guard('api_user')->fromUser($user);
            return $this->response->array([
                'status_code' => '200',
                'message' => '认证成功',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => Auth::guard('api_user')->factory()->getTTL() * 60
                ]
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $request->toArray());
            return $this->response->error('认证失败',401);
        }
    }
}
