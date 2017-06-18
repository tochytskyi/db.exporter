<?php

use yii\db\Migration;

class m170618_144814_add_results_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('results', [
            'id' => $this->primaryKey(),
            'post_title' => $this->text(),
            'post_content' => $this->text(),
            'dump_name' => $this->string(255)
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('results');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170618_144814_add_results_table cannot be reverted.\n";

        return false;
    }
    */
}
