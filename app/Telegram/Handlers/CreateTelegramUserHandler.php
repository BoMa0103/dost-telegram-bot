<?php

namespace App\Telegram\Handlers;

use App\Models\User;
use App\Services\Users\UsersService;
use Longman\TelegramBot\Entities\Message;

class CreateTelegramUserHandler
{
    public function __construct(
        private readonly UsersService $usersService
    )
    {
    }

    /**
     * @param Message $message
     * @return User
     */
    public function handle(Message $message) : User
    {
        $telegramUserId = $message->getFrom()->getId();
        $user = $this->usersService->findUserByTelegramId($telegramUserId);
        if($user) {
            return $user;
        }
        return $this->usersService->createUser([
            'telegram_id' => $telegramUserId,
            'name' => $message->getFrom()->getFirstName(),
            'phone' => $message->getContact() ? $message->getContact()->getPhoneNumber() : null,
            'lang' => $message->getFrom()->getLanguageCode()
        ]);
    }
}
