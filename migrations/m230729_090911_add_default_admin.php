<?php

use yii\db\Migration;

/**
 * Class m230729_090911_add_default_admin
 */
class m230729_090911_add_default_admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        (new \app\models\User([
            'username' => 'group4',
            'password' => \Yii::$app->getSecurity()->generatePasswordHash('4rfvcde3'),
            'email' => 'dev@sofona.com',
            'role_id' => \app\models\User::ROLE_ADMIN,
        ]))->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $user = \app\models\User::find()->where(['username' => 'group4'])->one();

        if ($user !== null) {
            $user->delete();
        }
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230729_090911_add_default_admin cannot be reverted.\n";

        return false;
    }
    */
}
