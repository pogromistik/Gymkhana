<?php

use yii\db\Migration;

/**
 * Handles the creation for table `about_slider`.
 */
class m160926_100800_create_about_slider_table extends Migration
{
    public function up()
    {
        $this->createTable('about_slider', [
            'id'      => $this->primaryKey(),
            'blockId' => $this->integer()->notNull(),
            'picture' => $this->string()->notNull(),
            'sort'    => $this->integer()
        ]);
        //$this->addForeignKey('about_slider_blockId', 'about_slider', 'blockId', 'about_block', 'id');
    }

    public function down()
    {
        $this->dropTable('about_slider');
    }
}
