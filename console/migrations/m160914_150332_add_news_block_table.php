<?php

use yii\db\Migration;

class m160914_150332_add_news_block_table extends Migration
{
    public function up()
    {
        $this->createTable('news_block', [
            'id'         => $this->primaryKey(),
            'newsId'     => $this->integer()->notNull(),
            'text'       => $this->text(),
            'sliderText' => $this->integer(),
            'sort'       => $this->integer()
        ]);
        $this->addForeignKey('news_block_newsId', 'news_block', 'newsId', 'news', 'id');
    }

    public function down()
    {
        $this->dropTable('news_block');
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
