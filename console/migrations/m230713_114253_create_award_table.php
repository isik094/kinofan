<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%award}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 */
class m230713_114253_create_award_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%award}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'name' => $this->string(),
            'win' => $this->tinyInteger(),
            'image_url' => $this->string(),
            'nomination_name' => $this->string(),
            'year' => $this->integer(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-award-cinema_id}}',
            '{{%award}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-award-cinema_id}}',
            '{{%award}}',
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
            '{{%fk-award-cinema_id}}',
            '{{%award}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-award-cinema_id}}',
            '{{%award}}'
        );

        $this->dropTable('{{%award}}');
    }
}
