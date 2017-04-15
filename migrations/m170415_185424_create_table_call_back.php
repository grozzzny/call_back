<?php

class m170415_185424_create_table_call_back extends \grozzzny\call_back\migrations\Migration
{
    public function safeUp()
    {
        $this->createTable('gr_call_back', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'email' => $this->string(),
            'phone' => $this->string(),
            'datetime' => $this->integer(),
            'ip' => $this->string(),
            'status' => $this->boolean()->defaultValue(1),
            'order_num' => $this->integer()->notNull(),
        ], $this->tableOptions);


        $this->insert('easyii_modules', [
            'name' => 'callback',
            'class' => 'grozzzny\call_back\Module',
            'title' => 'Call back',
            'icon' => 'font',
            'status' => 1,
            'settings' => '[]',
            'notice' => 0,
            'order_num' => 120
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('gr_call_back');
        $this->delete('easyii_modules',['name' => 'callback']);

        echo "m170415_185424_create_table_call_back cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170415_185424_create_table_call_back cannot be reverted.\n";

        return false;
    }
    */
}
