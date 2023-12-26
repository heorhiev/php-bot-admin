<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contact}}`.
 */
class m230729_085945_create_log_table extends Migration
{
    const CONTACT = '{{%log}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable(self::CONTACT, [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'text' => $this->text(),
            'status' => $this->smallInteger()->null(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::CONTACT);
    }
}
