<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cinema_box_office}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 */
class m230712_140339_create_cinema_box_office_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cinema_box_office}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'type' => $this->string(),
            'amount' => $this->decimal(16,2),
            'symbol' => $this->string(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-cinema_box_office-cinema_id}}',
            '{{%cinema_box_office}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-cinema_box_office-cinema_id}}',
            '{{%cinema_box_office}}',
            'cinema_id',
            '{{%cinema}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%cinema}}`
        $this->dropForeignKey(
            '{{%fk-cinema_box_office-cinema_id}}',
            '{{%cinema_box_office}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-cinema_box_office-cinema_id}}',
            '{{%cinema_box_office}}'
        );

        $this->dropTable('{{%cinema_box_office}}');
    }
}
