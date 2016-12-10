<?php

use app\components\Migrate;

class m160805_050116_lang extends Migrate
{
    public function safeUp()
    {
        $this->createTable('{{%lang}}', [
            'id' => $this->primaryKey(),
            'lang' => $this->string(255)->notNull(),
        ]);

        $this->insert('{{%lang}}',[
            'lang' => 'English'
        ]);
        $this->insert('{{%lang}}',[
            'lang' => 'Русский'
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%lang}}');
    }
}
