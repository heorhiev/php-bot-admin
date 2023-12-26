<?php

namespace app\models;

use app\models\search\LogSearch;
use app\traits\BasicBehaviorsTrait;
use yii\helpers\Url;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $status
 * @property string $name
 * @property string $text
 * @property int|null $created_at
 */
class Log extends \yii\db\ActiveRecord
{
    use BasicBehaviorsTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['text', 'name'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }


    public static function add($name, $text = ''): bool
    {
        $log = new Log();

        $log->name = $name;
        $log->text = $text;

        return $log->save();
    }
}
