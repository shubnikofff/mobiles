<?php

namespace app\modules\mobile\controllers;

use Yii;
use app\modules\mobile\models\Number;
use app\modules\mobile\models\NumberSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\mobile\models\Document;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * NumberController implements the CRUD actions for Number model.
 */
class NumberController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Number models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NumberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $exportDataProvider = $searchModel->export();
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_gridview', ['dataProvider' => $dataProvider]);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'exportDataProvider' => $exportDataProvider
        ]);
    }

    /**
     * @param null $number
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCreate($number = null)
    {
        $model = new Number(['scenario' => 'create']);
        $model->number = $number;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('numberSaved', 'Номер успешно создан');
            $view = 'update';
        } else {
            $view = 'create';
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($view, ['model' => $model]);
        } else throw new NotFoundHttpException("Запрашиваемая старница не найдена.");
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('numberSaved', 'Данные успешно обновлены');
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', ['model' => $model]);
        } else throw new NotFoundHttpException("Запрашиваемая старница не найдена.");
    }

    /**
     * Deletes an existing Number model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param \MongoId $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionAttachDocument($ownerId)
    {
        /** @var Number $number */
        $number = Number::findOne($ownerId);
        if ($number === null) {
            throw new \RuntimeException("Не найден номер к которому нужно прикрепить документ");
        }
        foreach (UploadedFile::getInstances(new Document(), 'file') as $file) {
            if (!$file->hasError) {
                $document = new Document([
                    'filename' => $file->name,
                    'contentType' => $file->type,
                    'ownerId' => $number->getPrimaryKey(),
                    'file' => $file
                ]);
                if (!$document->save()) {
                    throw new \RuntimeException("Не удалось сохранить документ '{$file->name}'");
                }
            }
        }
        echo Json::encode(['view' => $this->renderAjax('_document', ['ownerId' => $number->getPrimaryKey()])]);
        return;

    }

    public function actionDetachDocument($id)
    {
        $document = $this->findDocument($id);
        $ownerId = $document->ownerId;
        $document->delete();
        return Yii::$app->request->isAjax ? $this->renderAjax('_document', ['ownerId' => $ownerId]) : $this->renderPartial('_document', ['ownerId' => $ownerId]);
    }

    public function actionRenderDocument($id)
    {
        $document = $this->findDocument($id);
        header('Content-type: ' . $document->contentType);
        echo $document->getFileContent();
    }

    /**
     * Finds the Number model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param \MongoId $id
     * @return \app\modules\mobile\models\Number the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Number::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }

    /**
     * @param $id
     * @return Document
     * @throws NotFoundHttpException
     */
    protected function findDocument($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Документ не найден.');
        }
    }

    public function actionOwnerList($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Number::ownerList($q);
    }
}
