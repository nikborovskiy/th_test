<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_transaction}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m200518_074359_create_user_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_transaction}}', [
            'id' => $this->primaryKey()->comment('#'),
            'from_user' => $this->integer()->comment('Отправитель'),
            'to_user' => $this->integer()->comment('Получатель'),
            'value' => $this->float(2)->comment('Значение'),
            'created_at' => $this->datetime()->comment('Время операции'),
        ]);

        // creates index for column `from_user`
        $this->createIndex(
            '{{%idx-user_transaction-from_user}}',
            '{{%user_transaction}}',
            'from_user'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_transaction-from_user}}',
            '{{%user_transaction}}',
            'from_user',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_user`
        $this->createIndex(
            '{{%idx-user_transaction-to_user}}',
            '{{%user_transaction}}',
            'to_user'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_transaction-to_user}}',
            '{{%user_transaction}}',
            'to_user',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_transaction-from_user}}',
            '{{%user_transaction}}'
        );

        // drops index for column `from_user`
        $this->dropIndex(
            '{{%idx-user_transaction-from_user}}',
            '{{%user_transaction}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_transaction-to_user}}',
            '{{%user_transaction}}'
        );

        // drops index for column `to_user`
        $this->dropIndex(
            '{{%idx-user_transaction-to_user}}',
            '{{%user_transaction}}'
        );

        $this->dropTable('{{%user_transaction}}');
    }
}
