<?php

namespace backend\controllers;

use Yii;
use common\models\Video;
use common\models\VideoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\DirectoryStatus;


/**
 * VideoController implements the CRUD actions for Video model.
 */
class VideoController extends Controller
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
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new VideoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }


    public function actionCreate()
    {
        $model = new Video();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->link_video = 'https://www.youtube.com/watch?v=' . $model->link_video;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Video::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDeletions()
    {
        $errors = array();
        $deletions = Yii::$app->request->post('deletions');
        if (is_array($deletions)) {
            $deletions = array_unique($deletions);
            foreach ($deletions as $deleted_id) {
                $item = $this->findModel($deleted_id);
                if (is_object($item)) {
                    $result = $item->delete();
                    if ($result === false) {
                        $errors[] = $deleted_id;
                    }
                } else {
                    $errors[] = $deleted_id;
                }
            }
        }

        if (empty($errors)) {
            return json_encode(array('success' => true));
        }
        return json_encode(array('success' => false, 'errors' => $errors));
    }

    public function actionActivate()
    {
        $activateItems = Yii::$app->request->post('activateItems');
        $errors = [];
        $result = [];
        $status_id = DirectoryStatus::find()->asArray()->where(['value' => 1])->one();
        if (!empty($activateItems) && !empty($status_id['id'])) {
            $status_id = $status_id['id'];
            if (is_array($activateItems)) {
                $activateItems = array_unique($activateItems);
                foreach ($activateItems as $item_id) {
                    $item = $this->findModel($item_id);
                    if (is_object($item)) {
                        $item->status_id = $status_id;
                        if (!$item->save()) {
                            $errors[] = $item->id;
                        }
                    } else {
                        $errors[] = $item_id;
                    }
                }
                $result = [
                    'success' => true,
                    'message' => 'Активировано',
                    'test' => $status_id,
                    'errors' => $errors,
                ];
            }
        }

        return json_encode($result);
    }
}
