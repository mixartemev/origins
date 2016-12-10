<?php

use app\components\Migrate;

class m130524_201442_init extends Migrate
{
    public function up()
    {
        $tableOptions = null;
        /*if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }*/

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(43)->notNull(),
            'confirm_token' => $this->string(43)->unique(),
            'phone' => $this->smallInteger(3),
            'status' => $this->tinyInt()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->insert('{{%user}}',[
            'username' => 'admin',
            'name' => 'Админ',
            'email' => 'admin@test.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('123456'),//'$2y$13$27d3pqkKhwJ1/CLEg968DOR7thWgijTrWw2BVPRH4N7Z8vjZ/LBX6', // hash of password: 123456
            'auth_key' => Yii::$app->security->generateRandomString(),
            'status' => 3,
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
