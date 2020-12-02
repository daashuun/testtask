<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%work}}`.
 */
class m201202_155953_create_work_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%work}}', [
            'id' => $this->primaryKey(),
            'company' => $this->string(255),
            'position' => $this->string(255),
            'duties' => $this->text(),
            'startMonth' => $this->tinyInteger(),
            'startYear' => $this->smallInteger(),
            'endMonth' => $this->tinyInteger(),
            'endYear' => $this->smallInteger(),
            'now' => $this->boolean()->defaultValue(0),
            'resumeId' => $this->integer(),
            'time' => $this->string(255),
        ]);

        $this->addForeignKey(
            'resumeId',
            'work',
            'resumeId',
            'resume',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%work}}');
    }
}
