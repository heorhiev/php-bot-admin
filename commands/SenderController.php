<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use app\helpers\TelegramHelper;
use app\models\Contact;
use app\models\Log;
use app\models\Message;
use yii\console\Controller;
use yii\console\ExitCode;


class SenderController extends Controller
{
    public function actionSend(): int
    {
        /** @var Message $message */
        $message = Message::find()->where(['status' => 1])->one();

        if ($message) {
            try {
                TelegramHelper::send(Contact::find()->select('id')->column(), $message);
                $message->setSentStatus();
                Log::add('Сделана рассылка сообщения ' . $message->id);
            } catch (\Throwable $throwable) {
                $error = join(PHP_EOL, [
                    $throwable->getMessage(),
                    $throwable->getFile(),
                    $throwable->getLine()
                ]);

                Log::add('Ошибка отправки ' . $message->id, $error);

                \Yii::error($error);
                print_r($error);
                $message->setErrorStatus();
            }

            $message->save();
        }

        return ExitCode::OK;
    }
}