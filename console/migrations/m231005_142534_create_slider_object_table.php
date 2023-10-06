<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%slider_object}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%slider}}`
 */
class m231005_142534_create_slider_object_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%slider_object}}', [
            'id' => $this->primaryKey(),
            'slider_id' => $this->integer()->notNull(),
            'image_url' => $this->string(),
            'url' => $this->string(),
            'entity' => $this->string(),
            'entity_id' => $this->integer(),
        ]);

        // creates index for column `slider_id`
        $this->createIndex(
            '{{%idx-slider_object-slider_id}}',
            '{{%slider_object}}',
            'slider_id'
        );

        // add foreign key for table `{{%slider}}`
        $this->addForeignKey(
            '{{%fk-slider_object-slider_id}}',
            '{{%slider_object}}',
            'slider_id',
            '{{%slider}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%slider}}`
        $this->dropForeignKey(
            '{{%fk-slider_object-slider_id}}',
            '{{%slider_object}}'
        );

        // drops index for column `slider_id`
        $this->dropIndex(
            '{{%idx-slider_object-slider_id}}',
            '{{%slider_object}}'
        );

        $this->dropTable('{{%slider_object}}');
    }
}
