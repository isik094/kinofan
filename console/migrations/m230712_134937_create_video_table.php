<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%video}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 */
class m230712_134937_create_video_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%video}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'url' => $this->string(),
            'name' => $this->string(),
            'site' => $this->string(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-video-cinema_id}}',
            '{{%video}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-video-cinema_id}}',
            '{{%video}}',
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
            '{{%fk-video-cinema_id}}',
            '{{%video}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-video-cinema_id}}',
            '{{%video}}'
        );

        $this->dropTable('{{%video}}');
    }
}
