<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%distribution}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 * - `{{%country}}`
 * - `{{%company}}`
 */
class m230713_074516_create_distribution_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%distribution}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'type' => $this->string()(),
            'sub_type' => $this->string(),
            'date' => $this->timestamp(),
            're_release' => $this->tinyInteger(),
            'country_id' => $this->integer(),
            'company_id' => $this->integer(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-distribution-cinema_id}}',
            '{{%distribution}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-distribution-cinema_id}}',
            '{{%distribution}}',
            'cinema_id',
            '{{%cinema}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-distribution-country_id}}',
            '{{%distribution}}',
            'country_id'
        );

        // add foreign key for table `{{%country}}`
        $this->addForeignKey(
            '{{%fk-distribution-country_id}}',
            '{{%distribution}}',
            'country_id',
            '{{%country}}',
            'id',
            'CASCADE'
        );

        // creates index for column `company_id`
        $this->createIndex(
            '{{%idx-distribution-company_id}}',
            '{{%distribution}}',
            'company_id'
        );

        // add foreign key for table `{{%company}}`
        $this->addForeignKey(
            '{{%fk-distribution-company_id}}',
            '{{%distribution}}',
            'company_id',
            '{{%company}}',
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
            '{{%fk-distribution-cinema_id}}',
            '{{%distribution}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-distribution-cinema_id}}',
            '{{%distribution}}'
        );

        // drops foreign key for table `{{%country}}`
        $this->dropForeignKey(
            '{{%fk-distribution-country_id}}',
            '{{%distribution}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-distribution-country_id}}',
            '{{%distribution}}'
        );

        // drops foreign key for table `{{%company}}`
        $this->dropForeignKey(
            '{{%fk-distribution-company_id}}',
            '{{%distribution}}'
        );

        // drops index for column `company_id`
        $this->dropIndex(
            '{{%idx-distribution-company_id}}',
            '{{%distribution}}'
        );

        $this->dropTable('{{%distribution}}');
    }
}
