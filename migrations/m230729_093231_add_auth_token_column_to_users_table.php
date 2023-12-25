<?php
use yii\db\Migration;

/**
 * Handles the addition of column `auth_token` to table `{{%users}}`.
 */
class m230729_093231_add_auth_token_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'auth_token', $this->string(32)->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'auth_token');
    }
}
