<?php

namespace app\modules\mobile\controllers;

use app\modules\mobile\models\ExpenditureReportMaker;
use app\modules\mobile\models\OverrunReportMaker;
use app\modules\mobile\models\Report;
use app\modules\mobile\models\ReportSearch;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\modules\mobile\models\MTSXML;

/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'create' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Report models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReportSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Report model.
     * @param mixed $id
     * @param string $type
     * @param integer $above
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionView($id, $type, $above = 3000)
    {
        $model = $this->findModel($id);
        
        switch ($type) {
            case ReportSearch::TYPE_EXPENDITURE:
                $model->maker = new ExpenditureReportMaker($above);
                break;
            case ReportSearch::TYPE_OVERRUN:
                $model->maker = new OverrunReportMaker();
                break;
            default:
                throw new BadRequestHttpException;
        }
        
        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new Report model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $file = UploadedFile::getInstanceByName('operatorReport');

        if ($file->hasError) {
            echo Json::encode(['error' => "Ошибка загрузки файла {$file->name}"]);
            return;
        }

        /**
         * @var $mtsXML MTSXML
         */
        $mtsXML = simplexml_load_file($file->tempName, MTSXML::className());
        if (!$mtsXML || !$mtsXML->validate()) {
            echo Json::encode(['error' => 'Предоставленный файл имеет неверный формат']);
            return;
        }

        $report = Report::generate($mtsXML);
        if ($report instanceof Report) {
            \Yii::$app->session->setFlash('reportCreated', "Отчет &laquo;{$report->operator->name}&raquo; за "
                . \Yii::$app->formatter->asDate($report->getPeriodTimeStamp(), 'LLLL yyyy') . " успешно создан");
            $this->redirect(['index']);
        } else {
            echo Json::encode(['error' => 'Не удалось создать отчет']);
            return;
        }

    }

    public function actionRefreshOutsideDb($id)
    {
        return $this->renderAjax('_outsideDB', ['model' => $this->findModel($id)]);
    }

    /**
     * Finds the Report model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Report the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Report::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемый отчет не найден.');
        }
    }
}
