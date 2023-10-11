<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%compilation_cinema}}`.
 */
class m231009_114855_drop_compilation_cinema_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
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

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
