<?php

use yii\db\Migration;

class m160914_151159_add_news_slider extends Migration
{
    public function up()
    {
        $this->createTable('news_slider', [
            'id'      => $this->primaryKey(),
            'newsId'  => $this->integer()->notNull(),
            'blockId' => $this->integer()->notNull(),
            'picture' => $this->string()->notNull(),
            'sort'    => $this->integer()
        ]);
        //$this->addForeignKey('news_slider_newsId', 'news_slider', 'newsId', 'news', 'id');
        //$this->addForeignKey('news_slider_blockId', 'news_slider', 'blockId', 'news_block', 'id');
    }

    public function down()
    {
        $this->dropTable('news_slider');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
