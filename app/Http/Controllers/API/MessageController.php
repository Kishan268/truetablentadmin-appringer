<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Models\MessageMedia;
use App\Models\ChatMember;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;
use App\Constants\StringConstants;
use Illuminate\Support\Facades\Auth;
use App\AppRinger\ImageUtils;
use App\Http\Requests\API\SendMessageRequest;
use Exception;
use App\Events\PublicMessageEvent;
use App\AppRinger\SendMessage;


class MessageController extends Controller
{
	public function sendMessage(SendMessageRequest $request)
	{
		try {
			$user_id = Auth::guard('api')->user()->id;
			if ($request->recruiter_id == $user_id) {
				$sender_id = $request->recruiter_id;
			} else {
				$sender_id = $request->candidate_id;
			}
			$data = [];
			$data['message'] = isset($request->message) ? $request->message : '';
			$data['sender_id'] = $sender_id;
			$chat_id = Chat::chatExists($request);
			if ($chat_id == '') {
				$chat = Chat::add($request);
				$chat_id = $chat->id;
				ChatMember::addMembers($chat_id,$request->recruiter_id);
				ChatMember::addMembers($chat_id,$request->candidate_id);
			}
			$data['chat_id'] = $chat_id;
			$is_blocked = ChatMember::where('chat_id', $chat_id)->where('user_id','!=', $sender_id)->where('status', 'block')->first();
			if ($is_blocked) {
				$data['is_blocked_message'] = '1';
			} else {
				$data['is_blocked_message'] = '0';
			}

			$send_message = Message::add($data);

			if ($request->has('media') && $request->media != 'undefined' && $request->media != null  && $request->media != 'null' && $request->media != '' && isset($send_message)) {
				foreach ($request->media as $key => $file) {
					
				
					$message_id = $send_message->id;
					$file_type = $file->getClientOriginalExtension();
					$filename = 'TT' . str_pad($message_id, 5, '0', STR_PAD_LEFT) . "." . $file_type;
					$data['chat_id'] = $chat_id;
					$data['message_id'] = $message_id;
					$data['type'] = $file_type;
					$data['original_name'] = $file->getClientOriginalName();
					$data['size'] = $file->getSize();
					if (env('IS_S3_UPLOAD')) {
						$key = date('m-Y') . '/' . 'messages/' . $user_id . '/' . $filename;
						$s3Url = ImageUtils::uploadImageOnS3($file, $key);
						$data['path'] = $key;
						$update_media = MessageMedia::add($data);
					} else {
						$filename = ImageUtils::uploadImage($file, 'messages/' . $user_id, $filename);
						$data['path'] = url($filename);
						$update_media = MessageMedia::add($data);
					}
				}
			}
			if (isset($send_message)) {
				$message_id = $send_message->id;
				ChatMember::updateLastMessageSeen($chat_id, $message_id, $user_id);
				$reciever_data = ChatMember::getMessageReceiverData($chat_id,$user_id);
				$message['chat_id'] = $chat_id;
				$message['message'] = $request->message;

				if ($reciever_data != '') {
					$reciever_id = $reciever_data->user_id;
					$mute_duration  = $reciever_data->mute_duration;
		            $mute_duration_ends =  date('Y-m-d H:i:s', strtotime($reciever_data->muted_at . ' + ' . $mute_duration . ' days'));
		            $current_date_time = date('Y-m-d H:i:s');
					if (($reciever_data->is_muted != 1 || $mute_duration_ends < $current_date_time) && !$is_blocked) {
						SendMessage::sendMessageEvent('message_'.$reciever_id,$message);
					}

					SendMessage::sendChatEvent('chat_'.$reciever_id,$message);
				}

				return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, $chat_id));
			}
		} catch (\Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getChats(Request $request)
	{
		try {

			$user_id = Auth::guard('api')->user()->id;
			$chats = Chat::select('chats.*','chat_members.last_message_deleted_id AS last_message_deleted_id')
				->with('recruiter', 'candidate','members','job')
				->join('messages','messages.chat_id','chats.id')
				->join('chat_members','chat_members.chat_id','chats.id')
				->where('chat_members.user_id', $user_id)
				->where(function ($query) use ($user_id) {


					$query->where(function ($query) use ($user_id) {
						$query->where('chat_members.last_message_deleted_id', NULL);
					});

					$query->orWhere(function ($query) use ($user_id) {
						$query->where('chat_members.last_message_deleted_id','!=' ,NULL);
						$query->whereRaw('messages.id > last_message_deleted_id');
					});
				})
				
				->orderByDesc('chats.updated_at')->groupBy('chats.id')->get();
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, $chats));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getMessages($id)
	{
		try {
			$user_id = Auth::guard('api')->user()->id;
			$last_deleted_message_id = ChatMember::getLastDeletedMessageId($id, $user_id);
			$query = Message::with('medias', 'sender')->where('chat_id', $id);

			if ($last_deleted_message_id != NULL && $last_deleted_message_id != '') {
				$query->where('messages.id','>', $last_deleted_message_id);
			}
			$query->where(function ($query) use ($user_id) {


				$query->where(function ($query) use ($user_id) {
					$query->where('is_blocked_message', '1');
					$query->where('sender_id', $user_id);
				});

				$query->orWhere(function ($query) use ($user_id) {
					$query->where('is_blocked_message', '0');
				});
			});


			$messages =	$query->latest()->paginate(1000);
			$data['messages'] =	$messages;
			
			if (count($messages) > 0) {
				$message_id = $messages[0]->id;
				ChatMember::updateLastMessageSeen($id, $message_id, $user_id);
			}

			$data['user_unread_messages'] = Chat::getUserUnreadMessagesCount($user_id);

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, $data));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getChatMedia($id)
	{
		try {
			$chat = Chat::with('medias')->where('chats.id', $id)->first();

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, $chat->medias));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function updateChat($id, Request $request)
	{
		try {
			$user_id = Auth::guard('api')->user()->id;

			$request->request->add(['chat_id' => $id, 'user_id' => $user_id]);

			if ($request->has('is_muted') && $request->is_muted == '1') {
				$request->request->add(['muted_at' => date('Y-m-d H:i:s')]);
			}
			$chat = ChatMember::udpdateStatus($id, $request);
			$message = StringConstants::SUCCESSS;
			switch ($request->status) {
				case 'block':
					$message = StringConstants::BLOCK_USER_SUCCESS_MSG;
					break;
				case 'unblock':
					$message = StringConstants::UNBLOCK_USER_SUCCESS_MSG;
					break;
				case 'report':
					$message = StringConstants::REPORT_USER_SUCCESS_MSG;
					break;

				case 'report_block':
					$message = StringConstants::REPORT_AND_BLOCK_USER_SUCCESS_MSG;
					break;				
				default:
					$message = StringConstants::SUCCESSS;
					break;
			}

			if ($request->has('is_muted') && $request->is_muted == '1') {
				$message = StringConstants::CHAT_MUTE_SUCCESS_MSG;
			}elseif($request->has('is_muted') && $request->is_muted == '0') {
				$message = StringConstants::CHAT_UNMUTE_SUCCESS_MSG;
			}

			return Communicator::returnResponse(ResponseMessages::SUCCESS($message, []));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function delete($id)
	{
		try {
			$user_id = Auth::guard('api')->user()->id;
			$last_message = Message::getChatLastMessage($id, $user_id);
			$last_message_id = $last_message->id;
			$chat = ChatMember::updateLastDeletedMessage($id, $last_message_id, $user_id);

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, []));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function lastMessageUpdate(Request $request)
	{
		try {
			$user_id = Auth::guard('api')->user()->id;

			ChatMember::updateLastMessageSeen($request->chat_id, $request->message_id, $user_id);

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, []));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}
}
