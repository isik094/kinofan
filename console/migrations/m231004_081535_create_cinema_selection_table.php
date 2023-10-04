<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cinema_selection}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%selection}}`
 * - `{{%cinema}}`
 */
class m231004_081535_create_cinema_selection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cinema_selection}}', [
            'id' => $this->primaryKey(),
            'selection_id' => $this->integer()->notNull(),
            'cinema_id' => $this->integer(),
        ]);

        // creates index for column `selection_id`
        $this->createIndex(
            '{{%idx-cinema_selection-selection_id}}',
            '{{%cinema_selection}}',
            'selection_id'
        );

        // add foreign key for table `{{%selection}}`
        $this->addForeignKey(
            '{{%fk-cinema_selection-selection_id}}',
            '{{%cinema_selection}}',
            'selection_id',
            '{{%selection}}',
            'id',
            'CASCADE'
        );

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-cinema_selection-cinema_id}}',
            '{{%cinema_selection}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-cinema_selection-cinema_id}}',
            '{{%cinema_selection}}',
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
        // drops foreign key for table `{{%selection}}`
        $this->dropForeignKey(
            '{{%fk-cinema_selection-selection_id}}',
            '{{%cinema_selection}}'
        );

        // drops index for column `selection_id`
        $this->dropIndex(
            '{{%idx-cinema_selection-selection_id}}',
            '{{%cinema_selection}}'
        );

        // drops foreign key for table `{{%cinema}}`
        $this->dropForeignKey(
            '{{%fk-cinema_selection-cinema_id}}',
            '{{%cinema_selection}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-cinema_selection-cinema_id}}',
            '{{%cinema_selection}}'
        );

        $this->dropTable('{{%cinema_selection}}');
    }
}
