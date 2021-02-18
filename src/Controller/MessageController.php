<?php
declare(strict_types=1);

namespace App\Controller;

use Carbon\Carbon;
use App\Http\CustomResponse;
use App\Http\Exception\APIException;
use App\Model\Message;
use App\Model\Record;
use Ramsey\Uuid\Uuid;
use App\Model\User;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class MessageController extends Controller
{
    // user can send a message to another user
    public function sendMessage(Request $request, Response $response, $args)
    {
        $rules= [
            'receiver_id' => ['required'],
            'body' => ['required']
        ];

        // request validation
        $data= $this->validate($request, $rules);
        $id= $request->getAttribute('id');

        // validation
        if ($id ==$data['receiver_id']) {
            throw new APIException("Cannot send message to yourself!");
        }

        if (!User::query()->where('id', '=', ($data['receiver_id']))->exists()) {
            throw new APIException("Receiver doesn't exist!");
        }

        $message = Message::query()->create([
            'sender_id'=>$id,
            'receiver_id'=>$data['receiver_id'],
            'body'=>$data['body'],
            'uuid'=>Uuid::uuid4()
        ]);

        Record::query()->create([
            'user_id'=>$data['receiver_id'],
            'message_id'=>$message->id,
        ]);

        return CustomResponse::generate(true, $response, "Message sent successfully!")->withStatus(201);
    }

    // a user can delete a message that belongs to himself
    public function delete(Request $request, Response $response, $args)
    {
        // get authenticated user id
        $id =$request->getAttribute('id');

        // request validation
        $data= $this->validate($request, [
            'message_id' => ['required'],
        ]);

        $message = Message::query()->where('id', '=', $data['message_id'])->firstOrFail();

        // db validation
        if ($message->receiver_id != $id) {
            throw new APIException("You cannot delete another user's message!");
        }

        $message->delete();
        return CustomResponse::generate(true, $response, "Message successfully deleted!")->withStatus(200);
    }

    public function readMessage(Request $request, Response $response, $args)
    {
        // get message
        $id =$request->getAttribute('id');
        $messageRecord = Record::query()->where('message_id', '=', $args['id'])->firstOrFail();

        // validation
        if ($messageRecord->read_at != null) {
            throw new APIException("Message was already read!");
        }

        if ($messageRecord->user_id != $id) {
            throw new APIException("You do not have access to this message!");
        }

        $messageRecord->read_at = Carbon::now();
        $messageRecord->save();
        return CustomResponse::generate(true, $response, "Message read successfully!")->withStatus(200);
    }

    // get all messages that the user was either the receiver or sender
    public function getAllMessages(Request $request, Response $response, $args)
    {
        //get message
        $id =$request->getAttribute('id');
        $messages = Message::query()->where('sender_id', '=', $id)->orWhere('receiver_id', '=', $id)->orderBy('created_at', 'desc')->get(['body', 'created_at']);

        return CustomResponse::generate(true, $response, $messages)->withStatus(200);
    }

    // get all messages that the user was the sender
    public function getOutgoing(Request $request, Response $response, $args)
    {
        //get message
        $id = $request->getAttribute('id');
        $messages = Message::query()->where('sender_id', '=', $id)->orderBy('created_at', 'desc')->get(['body', 'created_at']);

        return CustomResponse::generate(true, $response, $messages)->withStatus(200);
    }

    // get all messages that the user was the receiver
    public function getIncoming(Request $request, Response $response, $args)
    {
        //get message
        $id= $request->getAttribute('id');
        $messages = Message::query()->where('receiver_id', '=', $id)->orderBy('created_at', 'desc')->get(['body', 'created_at']);

        return CustomResponse::generate(true, $response, $messages)->withStatus(200);
    }

    // get the message from uuid, where the user is either the receiver or sender
    public function getMessageFromId(Request $request, Response $response, $args)
    {
        //get message
        $message = Message::query()->where('uuid', '=', $args['uuid'])->firstOrFail();
        $id= $request->getAttribute('id');

        // validation: if user is sender or receiver of the message
        if ($id != (int)$message->sender_id && $id != (int)$message->receiver_id) {
            throw new APIException("You do not have permission to read this message!");
        }

        return CustomResponse::generate(true, $response, $message)->withStatus(200);
    }

    // get all messages with another user
    public function getMessagesWithUser(Request $request, Response $response, $args)
    {
        // get users
        $id= $request->getAttribute('id');
        $otherUserId = User::query()->where('uuid', '=', $args['uuid'])->firstOrFail()->id;

        // validation
        if ($id == $otherUserId) {
            throw new APIException("No messages found with self");
        }

        // get messages
        $messages = Message::query()->
            where(function ($query) use ($id, $otherUserId) {
                $query->where('sender_id', $id)->where('receiver_id', $otherUserId);
            })
            ->orWhere(function ($query) use ($id, $otherUserId) {
                $query->where('sender_id', $otherUserId)->where('receiver_id', $id);
            })->get(['body', 'created_at']);

        return CustomResponse::generate(true, $response, $messages)->withStatus(200);
    }

    // get outgoing messages with another user
    public function getMessagesSentToUser(Request $request, Response $response, $args)
    {
        // get users
        $id= $request->getAttribute('id');
        $otherUserId = User::query()->where('uuid', '=', $args['uuid'])->firstOrFail()->id;

        // validation
        if ($id == $otherUserId) {
            throw new APIException("No messages found with self");
        }

        $messages = Message::query()->where('sender_id', $id)->where('receiver_id', $otherUserId)->get(['body', 'created_at']);

        return CustomResponse::generate(true, $response, $messages)->withStatus(200);
    }

    // get incoming messages from another user
    public function getMessagesFromUser(Request $request, Response $response, $args)
    {
        // get users
        $id= $request->getAttribute('id');
        $otherUserId = User::query()->where('uuid', '=', $args['uuid'])->firstOrFail()->id;

        // validation
        if ($id == $otherUserId) {
            throw new APIException("No messages found with self");
        }

        // get messages
        $messages = Message::query()->where('sender_id', $otherUserId)->where('receiver_id', $id)->get(['body', 'created_at']);

        return CustomResponse::generate(true, $response, $messages)->withStatus(200);
    }
}
