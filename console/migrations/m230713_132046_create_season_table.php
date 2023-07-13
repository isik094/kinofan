<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%season}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 */
class m230713_132046_create_season_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%season}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'season_number' => $this->integer(),
            'episode_number' => $this->integer(),
            'name_ru' => $this->string(),
            'name_en' => $this->string(),
            'synopsis' => $this->string(),
            'release_date' => $this->timestamp(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-season-cinema_id}}',
            '{{%season}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-season-cinema_id}}',
            '{{%season}}',
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
            '{{%fk-season-cinema_id}}',
            '{{%season}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-season-cinema_id}}',
            '{{%season}}'
        );

        $this->dropTable('{{%season}}');
    }
}
