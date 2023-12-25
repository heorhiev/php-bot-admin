<?php

namespace app\helpers;

use app\models\Message;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;


class TelegramHelper
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public static function send(array $chatIds, Message $message): bool
    {
        $bot = new \TelegramBot\Api\BotApi(\Yii::$app->params['telegram_token']);

        foreach ($chatIds as $chatId) {
            $bot->sendMessage($chatId, $message->text);
        }

        return true;
    }
}