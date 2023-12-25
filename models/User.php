<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $password
 * @property string|null $email
 * @property int $role_id
 * @property string|null $created_at
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public const ROLE_ADMIN = 99;

    public const ROLE_USER = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['password', 'email'], 'string'],
            [['role_id'], 'integer'],
            [['created_at', 'auth_token'], 'safe'],
            [['username'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'role_id' => 'Role ID',
            'created_at' => 'Created At',
        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => null,
                'value' => date('Y-m-d H:i:s')
            ]
        ]);
    }

    public function getAuthTokens(): ActiveQuery
    {
        return $this->hasMany(AuthToken::class, ['user_id' => 'id']);
    }

    /**
     * @param $id
     * @return self|\yii\web\IdentityInterface|null
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * @param string $token
     * @param $type
     * @return self|\yii\db\ActiveRecord|\yii\web\IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::find()->innerJoinWith(['authTokens a'])->where(['a.token' => $token, 'a.is_expired' => 0])->one();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_token = Yii::$app->security->generateRandomString();
    }
}
