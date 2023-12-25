<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use app\helpers\TelegramHelper;
use app\models\Contact;
use app\models\Message;
use yii\console\Controller;
use yii\console\ExitCode;


class SenderController extends Controller
{
    public function actionSend(): int
    {
        /** @var Message $model */
        $model = Message::find()->where(['status' => 1])->one();

        if ($model) {
            try {
                TelegramHelper::send(Contact::find()->select('id')->column(), $model);
                $model->setSentStatus();
            } catch (\Throwable $throwable) {
                \Yii::error(print_r($throwable, 1));
                $model->setErrorStatus();
            }

            $model->save();
        }

        return ExitCode::OK;
    }
}