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

				$importer->import(new MultipleImportStrategy([
					'tableName' => Statistics::tableName(),
					'configs' => [
						[
							'attribute' => 'month',
							'value' => function($line) {
								$date = \DateTime::createFromFormat('Y-m-d', $line[0] . '-01');
								//для искуственного заполения даты на разные месяцы для тестинга
//								$i= rand(1,9);
//								$date = \DateTime::createFromFormat('Y-m-d', '2019-0' . $i . '-01');
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
						]
					],
					'skipImport' => function($line) {
						if (count($line) <= 1) {
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
		$flag = 0;

		if (Yii::$app->request->isPjax && $model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				$flag = 1;
				$dateTime = \DateTime::createFromFormat("Y-m-d", $model->from . '-01');
				$start = $dateTime->format('Y-m-d');
				$dateTime = \DateTime::createFromFormat("Y-m-d", $model->to . '-01');
				$end = $dateTime->format('Y-m-d');

				$sum = Statistics::find()
					->where(['between', 'month', $end, $start])
//					->createCommand()->getRawSql();
					->sum('money');

				$avg = Statistics::find()
					->where(['between', 'month', $end, $start])
					->average('money');

				$max = Statistics::find()
					->where(['between', 'month', $end, $start])
					->max('money');
			}

		}
		return $this->render('time', [
			'model' => $model,
			'totalAmount' => $sum,
			'average' => $avg,
			'maxTotal' => $max,
			'flag' => $flag,
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
