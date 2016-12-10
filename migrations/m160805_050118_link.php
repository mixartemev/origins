<?php

use app\components\Migrate;

class m160805_050118_link extends Migrate
{
    public function safeUp()
    {
        $this->createTable('{{%link}}', [
            'id' => $this->primaryKey(),
            'child_id' => $this->integer()->null(),
            'parent_id' => $this->integer()->null(),
            'description' => $this->string(255)->null(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('current_timestamp'),
            /*'user_updated' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),*/
        ]);
        $this->addForeignKey('fk_link_child', '{{%link}}', 'child_id', '{{%word}}', 'id');
        $this->addForeignKey('fk_link_parent', '{{%link}}', 'parent_id', '{{%word}}', 'id');
        $this->addForeignKey('fk_link_user', '{{%link}}', 'user_id', '{{%user}}', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%word}}');
    }
}