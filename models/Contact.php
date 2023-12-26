<?php

namespace app\models;

use app\traits\BasicBehaviorsTrait;
use yii\helpers\Url;

/**
 * This is the model class for table "contact".
 *
 * @property int $id
 * @property int $status
 * @property string $name
 * @property string|null $phone
 * @property string|null $birthday
 * @property int|null $command
 * @property int|null $created
 */
class Contact extends \yii\db\ActiveRecord
{
    use BasicBehaviorsTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%clients_bot_contact}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['id', 'status'], 'integer'],
            [['name', 'phone', 'birthday', 'command'], 'string'],
        ];
    }
}
