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
	public $date_ranges;
	public $from;
	public $to;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			[['date_ranges'], 'string'],
			[['from', 'to'], 'safe'],
			[['date_ranges'], 'required'],
		];
	}
}