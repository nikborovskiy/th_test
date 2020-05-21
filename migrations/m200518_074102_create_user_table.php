<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m200518_074102_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->comment('#'),
            'username' => $this->string()->comment('Никнейм'),
            'balance' => $this->float(2)->comment('Баланс'),
            'status' => $this->integer(2)->defaultValue(1)->comment('Статус'),
            'created_at' => $this->datetime()->comment('Время создания'),
            'updated_at' => $this->timestamp()->comment('Время изменения'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
