<?php

use yii\db\Migration;

/**
 * Class m180916_162109_add_translate_to_news
 */
class m180916_162109_add_translate_to_news extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn(\common\models\AssocNews::tableName(), 'titleEn', $this->string());
		$this->addColumn(\common\models\AssocNews::tableName(), 'previewTextEn', $this->text());
		$this->addColumn(\common\models\AssocNews::tableName(), 'fullTextEn', $this->text());
		$this->addColumn(\common\models\AssocNews::tableName(), 'prevImg', $this->text());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn(\common\models\AssocNews::tableName(), 'titleEn');
		$this->dropColumn(\common\models\AssocNews::tableName(), 'previewTextEn');
		$this->dropColumn(\common\models\AssocNews::tableName(), 'fullTextEn');
		$this->dropColumn(\common\models\AssocNews::tableName(), 'prevImg');
	}
}
