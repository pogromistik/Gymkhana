<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Points`.
 */
class m170310_041520_create_Points_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('Points', [
            'id' => $this->integer()->unique()->notNull(),
	        'point' => $this->integer()->notNull()
        ]);
        $this->addPrimaryKey('Points_pk_id', 'Points', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('Points');
    }
}
