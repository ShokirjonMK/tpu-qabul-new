<?php

use yii\db\Migration;

class m250619_133911_add_user_and_student_column_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-student-user_id', '{{%student}}', 'user_id');
        $this->createIndex('idx-student-branch_id', '{{%student}}', 'branch_id');

        // User jadvali
        $this->createIndex('idx-user-status', '{{%user}}', 'status');
        $this->createIndex('idx-user-user_role', '{{%user}}', 'user_role');
        $this->createIndex('idx-user-cons_id', '{{%user}}', 'cons_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250619_133911_add_user_and_student_column_index cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250619_133911_add_user_and_student_column_index cannot be reverted.\n";

        return false;
    }
    */
}
