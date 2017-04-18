<?php

class m170418_073014_add_column_description extends \grozzzny\call_back\migrations\Migration
{
    public function safeUp()
    {
        $this->addColumn('gr_call_back', 'description', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn('gr_call_back', 'description');
        echo "m170418_073014_add_column_description cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170418_073014_add_column_description cannot be reverted.\n";

        return false;
    }
    */
}
