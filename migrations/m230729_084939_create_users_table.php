<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m230729_084939_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey()->unsigned(),
            'username' => $this->string(),
            'password' => $this->text(),
            'email' => $this->text(),
            'role_id' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime(),
        ], $tableOptions ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
