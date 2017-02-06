<?php

namespace console\controllers;

use common\models\AboutBlock;
use common\models\AboutSlider;
use common\models\Album;
use common\models\City;
use common\models\Contacts;
use common\models\DopPage;
use common\models\Files;
use common\models\GroupMenu;
use common\models\HelpProject;
use common\models\Layout;
use common\models\Link;
use common\models\MainMenu;
use common\models\MainPhoto;
use common\models\Marshal;
use common\models\MenuItem;
use common\models\News;
use common\models\NewsBlock;
use common\models\NewsSlider;
use common\models\Page;
use common\models\Regular;
use common\models\Track;
use common\models\User;
use common\models\Year;
use yii\console\Controller;
use yii\db\Query;

class RunController extends Controller
{
	public function actionInsertTables()
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$items = (new Query())->from('about_block')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new AboutBlock();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('pages')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Page();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('about_slider')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new AboutSlider();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('albums')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Album();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('cities')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new City();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('contacts')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Contacts();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('dop_pages')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new DopPage();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('files')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Files();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('groups_menu')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new GroupMenu();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('help_project')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new HelpProject();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('layouts')->all();
		foreach ($items as $block) {
			$item = new Layout();
			foreach ($item->attributes as $attribute => $value) {
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('links')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Link();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('main_menu')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new MainMenu();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('main_photo')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new MainPhoto();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('marshals')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Marshal();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('menu_items')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new MenuItem();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('news')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new News();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('news_block')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new NewsBlock();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('news_slider')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new NewsSlider();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('regular')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Regular();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('tracks')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Track();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('years')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Year();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		$transaction->commit();
	}
	
	public function actionFixes()
	{
		$track = Track::findOne(1);
		$track->documentId = 18;
		$track->save();
		
		$track = Track::findOne(2);
		$track->documentId = 17;
		$track->save();
		
		$track = Track::findOne(3);
		$track->documentId = 16;
		$track->save();
		
		AboutSlider::updateAll(['blockId' => 5], ['blockId' => 1]);
		AboutSlider::updateAll(['blockId' => 6], ['blockId' => 2]);
		AboutSlider::updateAll(['blockId' => 7], ['blockId' => 3]);
		AboutSlider::updateAll(['blockId' => 8], ['blockId' => 4]);
	}
}