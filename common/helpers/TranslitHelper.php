<?php

namespace common\helpers;

use common\models\TranslateMessage;

class TranslitHelper
{
	public static function translitFio($text)
	{
		$converter = [
			'а' => 'a', 'б' => 'b', 'в' => 'v',
			'г' => 'g', 'д' => 'd', 'е' => 'e',
			'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
			'и' => 'i', 'й' => 'y', 'к' => 'k',
			'л' => 'l', 'м' => 'm', 'н' => 'n',
			'о' => 'o', 'п' => 'p', 'р' => 'r',
			'с' => 's', 'т' => 't', 'у' => 'u',
			'ф' => 'f', 'х' => 'kh',
			'ц' => 'ts',
			'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
			'ь' => '`', 'ы' => 'y', 'ъ' => '``',
			'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
			
			'А' => 'A', 'Б' => 'B', 'В' => 'V',
			'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
			'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
			'И' => 'I', 'Й' => 'Y', 'К' => 'K',
			'Л' => 'L', 'М' => 'M', 'Н' => 'N',
			'О' => 'O', 'П' => 'P', 'Р' => 'R',
			'С' => 'S', 'Т' => 'T', 'У' => 'U',
			'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts',
			'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shch',
			'Ь' => '`', 'Ы' => 'Y', 'Ъ' => '``',
			'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
		];
		
		$words = explode(' ', $text);
		$resultWords = [];
		foreach ($words as $word) {
			$textArray = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
			$resultWord = '';
			foreach ($textArray as $i => $letter) {
				if (isset($converter[$letter])) {
					if (is_callable($converter[$letter])) {
						$nextLetter = isset($textArray[$i+1]) ? $textArray[$i+1] : null;
						if ($nextLetter) {
							$nextLetter = $converter[$nextLetter];
						}
						$resultWord .= $converter[$letter]($nextLetter);
					}
					else {
						$resultWord .= $converter[$letter];
					}
				} else {
					$resultWord .= $letter;
				}
			}
			$resultWords[] = $resultWord;
		}
		
		return implode(' ', $resultWords);
	}
	
	public static function translitCity($text)
	{
		$text = trim($text);
		if (\Yii::$app->language == TranslateMessage::LANGUAGE_RU) {
			return $text;
		}
		if (!preg_match("/[а-яёА-ЯЁ]/iu",$text)){
			return $text;
		};
		$converter = [
			'а' => 'a', 'б' => 'b', 'в' => 'v',
			'г' => 'g', 'д' => 'd', 'е' => 'e',
			'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
			'и' => 'i', 'й' => 'y', 'к' => 'k',
			'л' => 'l', 'м' => 'm', 'н' => 'n',
			'о' => 'o', 'п' => 'p', 'р' => 'r',
			'с' => 's', 'т' => 't', 'у' => 'u',
			'ф' => 'f', 'х' => 'kh',
			'ц' => 'ts',
			'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
			'ь' => '`', 'ы' => 'y', 'ъ' => '``',
			'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
			
			'А' => 'A', 'Б' => 'B', 'В' => 'V',
			'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
			'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
			'И' => 'I', 'Й' => 'Y', 'К' => 'K',
			'Л' => 'L', 'М' => 'M', 'Н' => 'N',
			'О' => 'O', 'П' => 'P', 'Р' => 'R',
			'С' => 'S', 'Т' => 'T', 'У' => 'U',
			'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts',
			'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shch',
			'Ь' => '`', 'Ы' => 'Y', 'Ъ' => '``',
			'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
		];
		
		$words = explode(' ', $text);
		$resultWords = [];
		foreach ($words as $word) {
			$textArray = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
			$resultWord = '';
			foreach ($textArray as $i => $letter) {
				if (isset($converter[$letter])) {
					if (is_callable($converter[$letter])) {
						$nextLetter = isset($textArray[$i+1]) ? $textArray[$i+1] : null;
						if ($nextLetter) {
							$nextLetter = $converter[$nextLetter];
						}
						$resultWord .= $converter[$letter]($nextLetter);
					}
					else {
						$resultWord .= $converter[$letter];
					}
				} else {
					$resultWord .= $letter;
				}
			}
			$resultWords[] = $resultWord;
		}
		
		return implode(' ', $resultWords);
	}
	
	public static function translitRegion($text)
	{
		$text = trim($text);
		if (\Yii::$app->language == TranslateMessage::LANGUAGE_RU) {
			return $text;
		}
		if (!preg_match("/[а-яёА-ЯЁ]/iu",$text)){
			return $text;
		};
		$converter = [
			'а' => 'a', 'б' => 'b', 'в' => 'v',
			'г' => 'g', 'д' => 'd', 'е' => 'e',
			'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
			'и' => 'i', 'й' => 'y', 'к' => 'k',
			'л' => 'l', 'м' => 'm', 'н' => 'n',
			'о' => 'o', 'п' => 'p', 'р' => 'r',
			'с' => 's', 'т' => 't', 'у' => 'u',
			'ф' => 'f', 'х' => 'kh',
			'ц' => 'ts',
			'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
			'ь' => '', 'ы' => 'y', 'ъ' => '``',
			'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
			
			'А' => 'A', 'Б' => 'B', 'В' => 'V',
			'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
			'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
			'И' => 'I', 'Й' => 'Y', 'К' => 'K',
			'Л' => 'L', 'М' => 'M', 'Н' => 'N',
			'О' => 'O', 'П' => 'P', 'Р' => 'R',
			'С' => 'S', 'Т' => 'T', 'У' => 'U',
			'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts',
			'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shch',
			'Ь' => '', 'Ы' => 'Y', 'Ъ' => '``',
			'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
		];
		
		$words = explode(' ', $text);
		$resultWords = [];
		foreach ($words as $word) {
			$textArray = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
			$resultWord = '';
			foreach ($textArray as $i => $letter) {
				if (isset($converter[$letter])) {
					if (is_callable($converter[$letter])) {
						$nextLetter = isset($textArray[$i+1]) ? $textArray[$i+1] : null;
						if ($nextLetter) {
							$nextLetter = $converter[$nextLetter];
						}
						$resultWord .= $converter[$letter]($nextLetter);
					}
					else {
						$resultWord .= $converter[$letter];
					}
				} else {
					$resultWord .= $letter;
				}
			}
			$resultWords[] = $resultWord;
		}
		
		return implode(' ', $resultWords);
	}
}