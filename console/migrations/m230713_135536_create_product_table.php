<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 */
class m230713_135536_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'name' => $this->string(),
            'image_url' => $this->string(),
            'site' => $this->string(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-product-cinema_id}}',
            '{{%product}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-product-cinema_id}}',
            '{{%product}}',
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
            '{{%fk-product-cinema_id}}',
            '{{%product}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-product-cinema_id}}',
            '{{%product}}'
        );

        $this->dropTable('{{%product}}');
    }
}
