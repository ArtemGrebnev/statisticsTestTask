<?php

namespace app\controllers;

use ruskid\csvimporter\CSVReader;
use ruskid\csvimporter\CSVImporter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use Yii;
use app\models\Statistics;
use app\models\StatisticsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use ruskid\csvimporter\MultipleImportStrategy;
use app\models\TimeForm;
use yii\db\Expression;
use yii\bootstrap\ActiveForm;
use yii\web\Response;
use yii\db;


/**
 * StatisticsController implements the CRUD actions for Statistics model.
 */
class StatisticsController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all Statistics models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new StatisticsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Finds the Statistics model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return Statistics the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Statistics::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}

	/**
	 * @return mixed
	 * @throws \yii\base\Exception
	 */
	public function actionLoad()
	{
		$model = new UploadForm();
		$importer = new CSVImporter;
		if (Yii::$app->request->isPost) {
			$model->file = UploadedFile::getInstance($model, 'file');

			if ($model->file && $model->validate()) {
				$importer->setData(new CSVReader([
					'filename' => $model->file->tempName,
					'startFromLine' => 2,
					'fgetcsvOptions' => [
						'delimiter' => ','
					]
				]));

				$hashData = Statistics::find()
					->select('hash_month_user_money')
					->from('statistics')
					->asArray()->all();

				$importer->import(new MultipleImportStrategy([
					'tableName' => Statistics::tableName(),
					'configs' => [
						[
							'attribute' => 'month',
							'value' => function($line) {
								$date = \DateTime::createFromFormat('Y-m-d', $line[0] . '-01');
								$date2 = $date->format('Y-m-d H:i:s');
								return $date2;
							},
						],
						[
							'attribute' => 'user_id',
							'value' => function($line) {
								return $line[1];
							},
						],
						[
							'attribute' => 'money',
							'value' => function($line) {
								return $line[2];
							},
						],
						[
							'attribute' => 'hash_month_user_money',
							'value' => function($line) {
								return md5($line[0] . $line[1] . $line[2]);
							},
						]
					],
					'skipImport' => function($line) use($hashData) {
						$hashMd5 = md5($line[0] . $line[1] . $line[2]);
						if (in_array($hashMd5, array_column($hashData, 'hash_month_user_money')) || count($line) <= 1) {
							return true;
						}
					}
				]));
				$this->refresh();
				$this->redirect(['index']);
			}
		}

		return $this->render('load', ['model' => $model]);
	}


	public function actionTime()
	{
		$model = new TimeForm();
		$statistics = [];

		if (Yii::$app->request->isPjax && $model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				$dateTime = \DateTime::createFromFormat("Y-m-d", $model->from . '-01');
				$start = $dateTime->format('Y-m-d');
				$dateTime = \DateTime::createFromFormat("Y-m-d", $model->to . '-01');
				$end = $dateTime->format('Y-m-d');
				$statistics['totalAmount'] = Statistics::find()
					->where(['between', 'month', $start, $end])
					->sum('money');

				$statistics['average'] = Statistics::find()
					->where(['between', 'month', $start, $end])
					->average('money');

				$statistics['maxTotal'] = Statistics::find()
					->where(['between', 'month', $start, $end])
					->max('money');

				$statistics['median'] = Statistics::find()
					->from('statistics')
					->where(['between', 'month', $start, $end])
					->orderBy('money ASC')
					->asArray()->all();


				$statistics['median'] = array_column($statistics['median'], 'money');
				$cntElem = count($statistics['median']);
				if ($cntElem) {
					if ($cntElem % 2 != 0 ) {
						$statistics['median'] = $statistics['median'][floor(($cntElem / 2))];
					} else {
						$statistics['median'] = ($statistics['median'][($cntElem / 2) - 1] + $statistics['median'][($cntElem / 2)]);
					}
				}
			}
		}

		return $this->render('time', [
			'model' => $model,
			'statistics' => $statistics
		]);
	}

	public function actionGraph()
	{
		$data = Statistics::find()
			->select([new Expression('SUM(money) as total_money, AVG(money) as avg_money'), 'month'])
			->groupBy('month')
			->asArray()->all();

		$arrMonthAndSum = [];

		foreach ($data as $value) {
			$arrMonthAndSum['month'][] = date('F', strtotime($value['month']));
			$arrMonthAndSum['total_money'][] = $value['total_money'];
			$arrMonthAndSum['avg_money'][] = $value['avg_money'];
		}

		return $this->render('graph', ['arrMonthAndSum' => $arrMonthAndSum]);
	}
}
