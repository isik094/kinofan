<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%compilation_cinema}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%compilation}}`
 * - `{{%cinema}}`
 */
class m230713_092957_create_compilation_cinema_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%compilation_cinema}}', [
            'id' => $this->primaryKey(),
            'compilation_id' => $this->integer(),
            'cinema_id' => $this->integer(),
        ]);

        // creates index for column `compilation_id`
        $this->createIndex(
            '{{%idx-compilation_cinema-compilation_id}}',
            '{{%compilation_cinema}}',
            'compilation_id'
        );

        // add foreign key for table `{{%compilation}}`
        $this->addForeignKey(
            '{{%fk-compilation_cinema-compilation_id}}',
            '{{%compilation_cinema}}',
            'compilation_id',
            '{{%compilation}}',
            'id',
            'CASCADE'
        );

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-compilation_cinema-cinema_id}}',
            '{{%compilation_cinema}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-compilation_cinema-cinema_id}}',
            '{{%compilation_cinema}}',
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
        // drops foreign key for table `{{%compilation}}`
        $this->dropForeignKey(
            '{{%fk-compilation_cinema-compilation_id}}',
            '{{%compilation_cinema}}'
        );

        // drops index for column `compilation_id`
        $this->dropIndex(
            '{{%idx-compilation_cinema-compilation_id}}',
            '{{%compilation_cinema}}'
        );

        // drops foreign key for table `{{%cinema}}`
        $this->dropForeignKey(
            '{{%fk-compilation_cinema-cinema_id}}',
            '{{%compilation_cinema}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-compilation_cinema-cinema_id}}',
            '{{%compilation_cinema}}'
        );

        $this->dropTable('{{%compilation_cinema}}');
    }
}
