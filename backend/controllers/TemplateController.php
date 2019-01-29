<?php

namespace backend\controllers;

use Yii;
use common\models\Template;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;

/**
 * TemplateController implements the CRUD actions for Form model.
 */
class TemplateController extends Controller
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
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Template::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new Template();
        $partsPath = Yii::getAlias('@app/../frontend/views/page-templates/');
        $ext = '.twig';

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (!empty($model->twig_content)) {
                    FileHelper::createDirectory($partsPath, $mode = 0755, true);
                    file_put_contents($partsPath . $model->code . $ext, $model->twig_content);
                }
                if (!$model->save()) {
                    throw new yii\base\ErrorException('Произошла ошибка');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $path = Yii::getAlias('@app/../frontend/views/page-templates/default');
            $model->layout_twig = file_get_contents($path . '/layout.twig');
            $model->add_twig = file_get_contents($path . '/add.twig');
            $model->error_twig = file_get_contents($path . '/error.twig');
            $model->categories_twig = file_get_contents($path . '/categories.twig');
            $model->index_twig = file_get_contents($path . '/index.twig');
            $model->style_css = file_get_contents($path . '/style.css');

            return $this->render('create', [
                'model' => $model,
                'templates' => 'templates',
            ]);
        }
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $dir = $model->attributes['code'];
            if (!empty($dir)) {
                $pathupdate = Yii::getAlias('@app/../frontend/views/page-templates/' . $dir . '/');
                $model->layout_twig = file_get_contents($pathupdate . 'layout.twig');
                $model->add_twig = file_get_contents($pathupdate . 'add.twig');
                $model->style_css = file_get_contents($pathupdate . 'style.css');
                $model->error_twig = file_get_contents($pathupdate . 'error.twig');
                $model->categories_twig = file_get_contents($pathupdate . 'categories.twig');
                $model->index_twig = file_get_contents($pathupdate . 'index.twig');
            }
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
        if (($model = Template::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
