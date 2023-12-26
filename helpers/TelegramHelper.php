<?php

namespace app\helpers;

use app\models\Contact;
use app\models\Log;
use app\models\Message;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\InputMedia\ArrayOfInputMedia;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;
use TelegramBot\Api\Types\InputMedia\InputMediaVideo;


class TelegramHelper
{
    protected static $bot;

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public static function send(array $chatIds, Message $message): bool
    {
        foreach ($chatIds as $chatId) {
            try {
                self::sendMessage($chatId, $message->text);
                self::sendFiles($chatId, $message->getFilesInfo());
            } catch (\Throwable $throwable) {
                Log::add('Ошибка отправки ' . $message->id . ' пользователю ' . $chatId, ErrorHelper::getErrorText($throwable));
            }
        }

        return true;
    }


    protected static function sendMessage($chatId, $text): void
    {
        self::getBot()->sendMessage($chatId, $text);
    }


    protected static function sendFiles($chatId, $files): void
    {
        if (empty($files)) {
            return;
        }

        $media = new ArrayOfInputMedia();

        foreach ($files as $file) {
            switch ($file['type']) {
                case 'image':
                    $media->addItem(new InputMediaPhoto($file['url']));
                    break;
                case 'video':
                    $media->addItem(new InputMediaVideo($file['url']));
                    break;
                default:
                    self::getBot()->sendDocument($chatId, new \CURLFile($file['full_path']));
            }
        }

        if ($media->count()) {
            self::getBot()->sendMediaGroup($chatId, $media);
        }
    }


    protected static function getBot(): \TelegramBot\Api\BotApi
    {
        if (empty(self::$bot)) {
            self::$bot = new \TelegramBot\Api\BotApi(\Yii::$app->params['telegram_token']);
        }

        return self::$bot;
    }
}