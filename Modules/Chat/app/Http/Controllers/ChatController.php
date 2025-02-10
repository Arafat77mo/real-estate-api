<?php

namespace Modules\Chat\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Chat\App\Http\Requests\ChatMessageRequest;
use Modules\Chat\App\Http\Requests\ChatThreadRequest;
use Modules\Chat\app\Services\ChatService;
use Modules\Chat\App\Transformers\ChatResource;
use Modules\Chat\App\Transformers\MessageResource;
use Modules\Properties\app\Helpers\ResponseData;
use mysql_xdevapi\Exception;

class ChatController extends Controller
{
    public function __construct(protected ChatService $chatService)
    {

    }


    public function  sendMessage(ChatMessageRequest $chatMessageRequest,)
    {
        try{
            $message = $this->chatService->sendMessage($chatMessageRequest->validated());

            return ResponseData::send( trans('messages.success'),trans('messages.success') ,new MessageResource($message));

        } catch (Exception $e) {
            return ResponseData::send( trans('messages.error'), trans('messages.error'), [
                'error' => $e->getMessage(),
            ]);
        }

    }


     public  function getMessages(ChatThreadRequest $chatThreadRequest)
     {
         $messages =$this->chatService->getMessages($chatThreadRequest->validated());

         return ResponseData::send( trans('messages.success'),trans('messages.success'), MessageResource::collection($messages));


     }


     public function getUserThreads()
     {
         $threads=$this->chatService->getUserThreads() ;

         return ResponseData::send(trans('messages.success'),trans('messages.success'), $threads);
     }
}
