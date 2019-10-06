<?php
namespace app\models;

use yii\base\Model;

/**
 * UploadForm is the model behind the upload form.
 */
class TimeForm extends Model
{
	/**
	 * @var UploadedFile file attribute
	 */
	public $from;
	public $to;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			[['from', 'to'], 'safe'],
			[['from', 'to'], 'required'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'from' => 'Starting Date',
		];
	}
}