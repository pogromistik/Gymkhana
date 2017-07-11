<?php

use yii\db\Migration;

class m170711_051133_i18n_init extends Migration
{
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('TranslateMessageSource', [
			'id'       => $this->primaryKey(),
			'category' => $this->string(),
			'message'  => $this->text(),
			'status'   => $this->smallInteger()->notNull()->defaultValue(0)
		], $tableOptions);
		
		$this->createTable('TranslateMessage', [
			'id'          => $this->integer()->notNull(),
			'language'    => $this->string(16)->notNull(),
			'translation' => $this->text(),
		], $tableOptions);
		
		$this->addPrimaryKey('pk_TranslateMessage_id_language', 'TranslateMessage', ['id', 'language']);
		$this->addForeignKey('fk_TranslateMessageSource_message', 'TranslateMessage', 'id', 'TranslateMessageSource', 'id', 'CASCADE', 'RESTRICT');
	}
	
	public function safeDown()
	{
		$this->dropForeignKey('fk_TranslateMessageSource_message', 'message');
		$this->dropTable('TranslateMessage');
		$this->dropTable('TranslateMessageSource');
	}
}
