<?php

namespace app\modules\mobile\controllers;

use app\modules\mobile\models\Number;
use app\modules\mobile\models\Trip;
use app\modules\mobile\models\TripSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

class TripController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new TripSearch(['scenario' => 'search']);
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        return Yii::$app->request->isAjax ? $this->renderAjax('_gridview', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) : $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {
        $model = new Trip(['scenario' => 'edit']);
        $model->rentNumberDate = Yii::$app->formatter->asDate(time(), 'php:d.m.Y');
        $view = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $view = 'view';
            Yii::$app->session->setFlash('tripSaved', 'Данные о командировке успешно сохранены');
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax($view, ['model' => $model]);
        } else throw new NotFoundHttpException('Запрашиваемая страница не найдена.');

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('edit');
        $view = 'update';

        if (!$model->load(Yii::$app->request->post())) {
            $model->mobileNumber = $model->number->number;
            $model->employeeName = $model->employee->fullName;
            $model->employeePost = $model->employee->post;
            $model->beginDate = Yii::$app->formatter->asDate($model->duration['from']->sec, 'php:d.m.Y');
            $model->endDate = Yii::$app->formatter->asDate($model->duration['to']->sec, 'php:d.m.Y');
            $model->rentNumberDate = Yii::$app->formatter->asDate($model->numberPossession['from']->sec, 'php:d.m.Y');
        } elseif ($model->save()) {
            Yii::$app->session->setFlash('tripSaved', 'Данные о командировке успешно сохранены');
            $view = 'view';
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax($view, ['model' => $model]);
        } else throw new NotFoundHttpException('Запрашиваемая страница не найдена.');

    }

    public function actionComplete($id)
    {
        $model = $this->findModel($id);
        $view = $model->complete() ? 'view' : 'update';
        return $this->renderAjax($view,['model'=>$model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->redirect(['index']);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('view', ['model' => $model]);
        } else throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
    }

    public function actionNumberList($q = null)
    {
        $query = Number::find()->select(['number'])->where(['like', 'number', $q])->asArray();
        $query->andWhere(['options' => Number::OPTION_TRIP]);
        $out = [];
        foreach ($query->all() as $item) {
            $out[] = ['value' => $item['number']];
        }
        echo Json::encode($out);
    }

    /**
     * @param $id
     * @return Trip
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        if (($model = Trip::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }
}

