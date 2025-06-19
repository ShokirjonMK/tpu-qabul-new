<?php

use yii\db\Migration;

class m250619_135340_add_exam_index_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            'idx-student_perevot-filter',
            'student_perevot',
            ['status', 'is_deleted']
        );

        // StudentDtm
        $this->createIndex(
            'idx-student_dtm-filter',
            'student_dtm',
            ['status', 'is_deleted']
        );

        // StudentMaster
        $this->createIndex(
            'idx-student_master-filter',
            'student_master',
            ['status', 'is_deleted']
        );

        // Exam
        $this->createIndex(
            'idx-exam-filter',
            'exam',
            ['is_deleted', 'status']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250619_135340_add_exam_index_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250619_135340_add_exam_index_column cannot be reverted.\n";

        return false;
    }
    */
}
