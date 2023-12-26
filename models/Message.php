<?php

namespace app\models;

use app\helpers\FilesHelper;
use app\traits\BasicBehaviorsTrait;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

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
            [['uploadFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, pdf, mov, mp4, avi', 'maxSize' => 1024 * 1024 * 2, 'maxFiles' => 6],
        ];
    }


    public function afterDelete()
    {
        parent::afterDelete();

        if ($this->files) {
            foreach ($this->getFilesInfo() as $file) {
                if (file_exists($file['full_path'])) {
                    unlink($file['full_path']);
                }
            }
        }
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

    /**
     * @return array{url: string, type: string}[]
     */
    public function getFilesInfo(): ?array
    {
        if (empty($this->files)) {
            return null;
        }

        foreach ($this->files as $file) {
            $result[] = [
                'type' => $file['type'],
                'url' => FilesHelper::getBaseUrl() . $file['path'],
                'full_path' => FilesHelper::getBaseDir() . $file['path'],
            ];
        }

        return $result ?? null;
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
        $subDir = date('Y-m-d');
        $uploadDir = FilesHelper::getBaseDir() . $subDir; // Use a safe directory outside of webroot

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
        }

        foreach ($this->uploadFiles as $key => $uploadFile) {
            /** @var UploadedFile $uploadFile */

            $fileName = \Yii::$app->security->generateRandomString(10) . '.' . $uploadFile->extension;
            $filePath = "{$uploadDir}/{$fileName}";

            if ($uploadFile->saveAs($filePath)) {
                $this->uploadFiles[$key] = null;
                $results[] = [
                    'type' => explode('/', $uploadFile->type)[0],
                    'path' => "{$subDir}/{$fileName}",
                ];
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
