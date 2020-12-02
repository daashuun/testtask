<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%resume}}`.
 */
class m201202_155939_create_resume_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%resume}}', [
            'id' => $this->primaryKey(),
            'photo' => $this->string(255),
            'surname' => $this->string(255),
            'name' => $this->string(255),
            'middlename' => $this->string(255),
            'birtday' => $this->date(),
            'sex' => $this->boolean()->defaultValue(1),
            'sity' => $this->smallInteger()->defaultValue(1),
            'email' => $this->string(255),
            'phone' => $this->string(255),
            'specialization' => $this->smallInteger(),
            'salary' => $this->integer(),
            'employment' => $this->json(),
            'schedule' => $this->json(),
            'experience' => $this->boolean()->defaultValue(0),
            'about' => $this->text(),
            'changed' => $this->dateTime(),
            'view' => $this->integer()->defaultValue(0),
            'whereAll' => $this->boolean()->defaultValue(1),
            'exp' => $this->tinyInteger()->defaultValue(0),
        ]);
        
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%resume}}');
    }
}
