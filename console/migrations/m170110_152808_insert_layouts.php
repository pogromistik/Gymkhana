<?php

use yii\db\Migration;

class m170110_152808_insert_layouts extends Migration
{
	public function safeUp()
	{
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'main',
			'title' => 'Главная страница'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'russia',
			'title' => 'Россия'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'ural',
			'title' => 'Урал'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'photoGallery',
			'title' => 'Фотогалерея'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'videoGallery',
			'title' => 'Видеогалерея'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'competitions',
			'title' => 'Соревнования'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'notFound',
			'title' => 'Страница не найдена'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'inDevelop',
			'title' => 'Страница в разработке'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'tracks',
			'title' => 'Скачать фигуры'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'about',
			'title' => 'О проекте'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'regulars',
			'title' => 'Правила'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'marshals',
			'title' => 'Маршалы'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'helpProject',
			'title' => 'Помочь проекту'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'address',
			'title' => 'Адреса'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'sponsors',
			'title' => 'Спонсоры'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'allNews',
			'title' => 'Новости'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'news',
			'title' => 'Новость'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'albums',
			'title' => 'Альбомы'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'album',
			'title' => 'Альбом'
		]);
	}
	
	public function safeDown()
	{
		return true;
	}
}
