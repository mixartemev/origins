<?php

use app\components\Migrate;

class m160805_050117_word extends Migrate
{
    public function safeUp()
    {
        $this->createTable('{{%word}}', [
            'id' => $this->primaryKey(),
            'word' => $this->string(255)->notNull()->unique(),
            'description' => $this->string(255)->null(),
            'lang_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('current_timestamp'),
        ]);
        $this->addForeignKey('fk_word_lang', '{{%word}}', 'lang_id', '{{%lang}}', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%word}}');
    }
}