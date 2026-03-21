<?php

namespace App\Http\Controllers\Api;

use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/conversations",
     *     summary="Get all conversations for current user",
     *     tags={"Messages"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Conversations retrieved successfully"
     *     )
     * )
     */
    public function conversations(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $conversations = $user->conversations()
            ->with(['users', 'messages' => function($q) { 
                $q->latest()->limit(1); 
            }])
            ->orderByDesc('last_message_at')
            ->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($conversations, 'Conversations retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/conversations",
     *     summary="Create a new conversation",
     *     tags={"Messages"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id"},
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="clinic_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Conversation created successfully"
     *     )
     * )
     */
    public function createConversation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'clinic_id' => 'nullable|exists:clinics,id',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        
        // Check if conversation already exists
        $existingConversation = $user->conversations()
            ->whereHas('users', function ($q) use ($request) {
                $q->where('users.id', $request->user_id);
            })
            ->first();

        if ($existingConversation) {
            return $this->successResponse($existingConversation, 'Conversation already exists');
        }

        $clinicId = $request->clinic_id;
        if (!$clinicId) {
            if ($user->isStaff()) {
                $clinicId = $user->staff->clinic_id;
            } elseif ($user->isClinicAdmin()) {
                $clinicId = $user->clinic->id;
            }
        }

        $conversation = Conversation::create([
            'clinic_id' => $clinicId,
            'last_message_at' => now(),
        ]);

        $conversation->users()->attach([$user->id, $request->user_id]);

        $conversation->load('users');

        return $this->successResponse($conversation, 'Conversation created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/conversations/{id}/messages",
     *     summary="Get messages for a conversation",
     *     tags={"Messages"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=50)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Messages retrieved successfully"
     *     )
     * )
     */
    public function messages(Conversation $conversation, Request $request): JsonResponse
    {
        $user = auth()->user();
        
        // Check if user is part of conversation
        if (!$conversation->users()->where('users.id', $user->id)->exists()) {
            return $this->forbiddenResponse('You are not part of this conversation');
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->oldest()
            ->paginate($request->get('per_page', 50));

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return $this->paginatedResponse($messages, 'Messages retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/conversations/{id}/messages",
     *     summary="Send a message",
     *     tags={"Messages"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"body"},
     *             @OA\Property(property="body", type="string", example="Hello, how are you?"),
     *             @OA\Property(property="type", type="string", enum={"text","image","file"}, default="text")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Message sent successfully"
     *     )
     * )
     */
    public function sendMessage(Conversation $conversation, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'type' => 'sometimes|in:text,image,file',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        
        // Check if user is part of conversation
        if (!$conversation->users()->where('users.id', $user->id)->exists()) {
            return $this->forbiddenResponse('You are not part of this conversation');
        }

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'body' => $request->body,
            'type' => $request->get('type', 'text'),
        ]);

        $conversation->update(['last_message_at' => now()]);

        $message->load('sender');

        // Broadcast message
        broadcast(new MessageSent($message))->toOthers();

        return $this->successResponse($message, 'Message sent successfully', 201);
    }

    /**
     * @OA\Post(
     *     path="/api/messages/{id}/read",
     *     summary="Mark a message as read",
     *     tags={"Messages"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Message marked as read"
     *     )
     * )
     */
    public function markAsRead(Message $message): JsonResponse
    {
        $user = auth()->user();
        
        if ($message->sender_id === $user->id) {
            return $this->errorResponse('You cannot mark your own message as read', 400);
        }

        $message->markAsRead();

        return $this->successResponse($message, 'Message marked as read');
    }
}
