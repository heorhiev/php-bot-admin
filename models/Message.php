<?php

namespace app\models;

use app\traits\BasicBehaviorsTrait;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $status
 * @property string $text
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property array $files
 */
class Message extends \yii\db\ActiveRecord
{
    use BasicBehaviorsTrait;

    public $uploadFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['text'], 'required', 'when' => function() {
                return empty($this->uploadFiles);
            }],
            [['created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'text'], 'string'],
            [['status'], 'default', 'value' => 0],
            [['files'], 'safe'],
            [['uploadFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2, 'maxFiles' => 6],
        ];
    }

    public function getImagePath(): ?string
    {
        if (empty($this->photo)) {
            return null;
        }

        return Url::to(["/uploads/{$this->photo}"], true);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'text' => 'Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }


    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatuses(), (int) $this->status);
    }


    public function setSendStatus(): self
    {
        $this->status = 1;
        return $this;
    }


    public function setErrorStatus(): self
    {
        $this->status = 5;
        return $this;
    }


    public function setSentStatus(): self
    {
        $this->status = 10;
        return $this;
    }


    public function upload(): ?array
    {
        \Yii::debug('save files');
        $uploadDir = \Yii::getAlias('@webroot/uploads'); // Use a safe directory outside of webroot
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
        }

        foreach ($this->uploadFiles as $key => $uploadFile) {
            $fileName = \Yii::$app->security->generateRandomString(10) . '.' . $uploadFile->extension;
            $filePath = $uploadDir . '/' . $fileName;
            if ($uploadFile->saveAs($filePath)) {
                $this->uploadFiles[$key] = null;
                $results[] = $fileName;
            }
        }

        return $results ?? null;
    }


    public static function getStatuses(): array
    {
        return [
            0 => 'Не отправлено',
            1 => 'Запланировано',
            5 => 'Ошибка',
            10 => 'Отправлено',
        ];
    }
}
