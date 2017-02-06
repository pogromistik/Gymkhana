<?php

use yii\db\Migration;

/**
 * Handles the creation for table `about_blocks`.
 */
class m160926_100732_create_about_blocks_table extends Migration
{
    public function up()
    {
        $this->createTable('AboutBlock', [
            'id'         => $this->primaryKey(),
            'text'       => $this->text(),
            'sliderText' => $this->string(),
            'sort'       => $this->integer()
        ]);
    }

    public function down()
    {
        $this->dropTable('AboutBlock');
    }
}
