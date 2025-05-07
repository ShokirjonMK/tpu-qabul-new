<?php

use yii\db\Migration;

class m250507_114817_alter_question_table_add_image_base64_long_text extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('questions', 'image_base64');
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        if (!$this->db->getTableSchema('questions')->getColumn('image_base64')) {
            $this->addColumn('questions', 'image_base64', $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext')->null());
        }
    }

    public function down()
    {
        $this->dropColumn('questions', 'image_base64');
    }
}
