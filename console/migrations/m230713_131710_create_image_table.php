<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 */
class m230713_131710_create_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'image_url' => $this->string(),
            'preview_url' => $this->string(),
            'type' => $this->string(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-image-cinema_id}}',
            '{{%image}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-image-cinema_id}}',
            '{{%image}}',
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
            '{{%fk-image-cinema_id}}',
            '{{%image}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-image-cinema_id}}',
            '{{%image}}'
        );

        $this->dropTable('{{%image}}');
    }
}
