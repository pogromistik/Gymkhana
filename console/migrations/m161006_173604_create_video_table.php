<?php

use yii\db\Migration;

/**
 * Handles the creation for table `video`.
 */
class m161006_173604_create_video_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('Video', [
            'id'          => $this->primaryKey(),
            'title'       => $this->string()->notNull(),
            'typeId'      => $this->integer()->notNull(),
            'description' => $this->string(),
            'link'        => $this->string()->notNull(),
            'dateAdded'   => $this->integer()->notNull(),
            'dateUpdated' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('Video');
    }
}
