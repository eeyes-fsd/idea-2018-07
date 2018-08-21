<?php

namespace App\Http\Controllers\Api;

use App\Fractal\CustomManager;
use App\Http\Requests\ShowNotificationsRequest;
use App\Http\Requests\StorePrivateMessageRequest;
use App\Models\Organization;
use App\Models\Reply;
use App\Models\User;
use App\Notifications\PrivateMessage;
use App\Serializers\CustomSerializer;
use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.a');
    }

    public function index(ShowNotificationsRequest $request)
    {

        $user = $this->getUserOrActiveOrganization();
        $paginator = $user->getNotifications($request->except(['per_page','page']))->paginate($request->get('per_page',15));
        $notifications = $paginator->getCollection();
        $manager = new CustomManager();
        $manager->setSerializer(new CustomSerializer());
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);

        $resource = new Collection($notifications, new NotificationTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $data = $manager->createData($resource)->setKey('notifications')->toArray();

        return $data;
    }

    /**
     * @param StorePrivateMessageRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(StorePrivateMessageRequest $request)
    {
        if ($request->user_type === 'user') {
            $user = User::findOrFail($request->user_id);
        } elseif ($request->user_type === 'organization') {
            $user = Organization::findOrFail($request->user_id);
        } elseif ($request->user_type === 'all') {
            //todo permissions ,use this function as broadcasting?
        }
        $user->notify(new PrivateMessage($request->body));
        return $this->success(200,'站内信发送成功',['站内信发送成功']);
    }

    public function show()
    {
        //
    }


    public function destroy()
    {

    }

    public function read(Request $request)
    {
        $count = $this->getUserOrActiveOrganization()->readsNotifications($request->q);
        return $this->success(200,"{$count}条消息标记已读成功",["{$count}条消息标记已读成功"]);
    }
}
