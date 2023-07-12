<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%similar}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 */
class m230712_135302_create_similar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%similar}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'id_kp',
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-similar-cinema_id}}',
            '{{%similar}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-similar-cinema_id}}',
            '{{%similar}}',
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
            '{{%fk-similar-cinema_id}}',
            '{{%similar}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-similar-cinema_id}}',
            '{{%similar}}'
        );

        $this->dropTable('{{%similar}}');
    }
}
