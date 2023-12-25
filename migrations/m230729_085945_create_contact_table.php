<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contact}}`.
 */
class m230729_085945_create_contact_table extends Migration
{
    const CONTACT = '{{%contact}}';

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
            'status' => $this->smallInteger()->null(),
            'phone' => $this->string(255),
            'birthday' => $this->string(255),
            'command' => $this->string(255),
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
