<?php

namespace frontend\controllers;

use common\models\Categories;
use common\models\Template;
use common\models\Video;
use common\models\Votes;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout = '../page-templates/default/layout.twig';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex($cat = null)
    {
        $video_id = Yii::$app->request->post('video_id', false);

        if (!empty($cat)) {
            $searchCat = Categories::find()->where(['code' => $cat])->one();
            if ($searchCat == null) {
                throw new HttpException(404, 'Категории не существует');
            }
            $this->view->params['category_code'] = $searchCat->code;
            $video_query = Video::find()->joinWith('directoryStatus')->joinWith('categories')->orderBy(new Expression('rand()'))->where(['directory_statuses.value' => '1'])->andWhere(['categories.code' => $cat]);
        } else {
            $video_query = Video::find()->joinWith('directoryStatus')->orderBy(new Expression('rand()'))->where(['directory_statuses.value' => '1'])->andWhere(['category_id' => null]);
        }
        if ($video_id !== false) {
            $video = $video_query->andWhere(['not', ['link_video' => $video_id]])->one();
        } else {
            $video = $video_query->one();
        }

        if (is_object($video)) {
            $video->updateCounters(['views' => 1]);
            if (Yii::$app->request->isAjax) {
                return $video->link_video;
            } else {
                if (!empty($searchCat)) {
                    $template = Template::find()->where(['id' => $searchCat->template_id])->one();
                } else {
                    $template = null;
                }
                if (is_object($template)) {
                    $code = $template->code;
                } else {
                    $code = 'default';
                }
                try {
                    $this->layout = '../page-templates/' . $code . '/layout.twig';
                    return $this->render(Yii::getAlias('/page-templates/' . $code . '/index.twig'), ['video' => $video]);
                } catch (\yii\base\ViewNotFoundException $e) {
                    return $this->render(Yii::getAlias('/page-templates/default/index.twig'), ['video' => $video]);
                }
            }
        }
        throw new HttpException(404, 'Нет видео');
    }

    public function actionCategories()
    {
        $categories = Categories::find()->asArray()->all();
        return $this->render(Yii::getAlias('/page-templates/default/categories.twig'), ['categories' => $categories]);
    }

    public function actionAdd($cat=null)
    {
        if (!empty($cat)) {
            $category = Categories::find()->where(['code' => $cat])->one();
            if (!is_object($category)) {
                throw new HttpException(404, 'Категории не существует');
            }
            $template = Template::find()->where(['id' => $category->template_id])->one();
            if (is_object($template)) {
                $code = $template->code;
            } else {
                $code = 'default';
            }
            try {
                return $this->render(Yii::getAlias('page-templates/'. $code .'/add.twig'), ['category_id' => $category->id]);
            } catch (\yii\base\ViewNotFoundException $e) {
                return $this->render(Yii::getAlias('/page-templates/default/add.twig'), ['category_id' => $category->id]);
            }
        }
        return $this->render(Yii::getAlias('/page-templates/default/add.twig'));
    }


    public function actionAddSend()
    {
        if (Yii::$app->request->isAjax) {
            $arrayNames = Yii::$app->request->post('name_video');
            $arrayLinks = Yii::$app->request->post('link_video');
            $categoryId= Yii::$app->request->post('category_id', null);
            if (!empty($categoryId)) {
                if (!Categories::find()->where(['id' => $categoryId])->exists()) {
                    $result = [
                        'status' => 'error',
                        'message' => 'Категория не существует',
                    ];
                    return json_encode($result);
                }
            }

            if (count($arrayNames) != count($arrayLinks)) {
                $result = [
                    'status' => 'error',
                    'message' => 'Не соответствующий формат ссылок ' . count($arrayNames) . ' ' . count($arrayLinks),
                ];
                return json_encode($result);
            }

            $errors = array();
            $savedVideosCount = 0;
            $notSavedVideos = [];

            for ($count = 0; $count < count($arrayNames); $count++) {
                if (!empty($arrayLinks[$count])) {
                    $video = new Video();
                    $video->name = $arrayNames[$count];
                    $video->link_video = $arrayLinks[$count];
                    if (!empty($categoryId)) {
                        $video->category_id = $categoryId;
                        $video->status_id = 0;
                    } else {
                        $video->category_id = 12345;
                        $video->status_id = 1;
                    }
                    if ($video->save()) {
                        $savedVideosCount++;
                    } else {
                        $errors = [];
                        foreach($video->errors as $error) {
                            foreach($error as $e) {
                                $errors[] = $e;
                            }
                        }

                        $notSavedVideos[] = [
                            'name' => $arrayNames[$count],
                            'link' => $arrayLinks[$count],
                            'errors' => implode(', ', $errors),
                        ];
                    }
                }
            }
            if ($savedVideosCount>0) {
                $result = [
                    'status' => 'success',
                    'message' => 'Количество добавленных видео - '.$savedVideosCount.'<br/>',
                ];
                if (!empty($categoryId)) {
                    $result['message'] = $result['message'].'Видеоролики появятся на сайте после модерации.<br/>';
                }
            } else {
                $result = [
                    'status' => 'error',
                    'message' => 'Видео не добавлены<br/>',
                ];
            }
            if (!empty($notSavedVideos)) {
                foreach($notSavedVideos as $v) {
                    $result['message'] = $result['message'].'Не добавлено видео - '.$v['name'].'('.$v['link'].')'.'<br/>';
                    $result['message'] = $result['message'].'По причине - '.$v['errors'].'<br/>';
                }
            }
            return json_encode($result);
        } else {
            $result = [
                'status' => 'error',
                'message' => 'no is AJAX',
            ];
            return json_encode($result);
        }
    }

    public function actionRatings()
    {
        if (Yii::$app->request->isAjax) {
            $rating = Yii::$app->request->post('button');
            $srcVideo = Yii::$app->request->post('video');

            if (!empty($rating)) {
                $arVideo = Video::find()->where(['link_video' => $srcVideo])->one();
                if (is_object($arVideo)) {
                    $vote = Votes::find()->where(['video_id' => $arVideo->id, 'ip' => Yii::$app->request->userIP])->orderBy(['created' => SORT_DESC])->asArray()->one();
                } else {
                    $result = [
                        'success' => false,
                        'message' => 'Такого видео нет.',
                    ];
                    return json_encode($result);
                }

                $parseDateNow = time();
                $parseLastVote = strtotime($vote['created']);
                $validVote = 0;

                if ($vote['created'] == false) {
                    $parseLastVote = false;
                } else {
                    $validVote = (($parseDateNow - $parseLastVote) / 60 / 60);
                }

                if ($validVote > 24 || $parseLastVote == false) {
                    $newVote = new Votes();

                    $newVote->video_id = $arVideo->id;
                    $newVote->ip = Yii::$app->request->userIP;
                    $newVote->useragent = Yii::$app->request->userAgent;

                    if ($rating == 'like') {
                        $rating = 1;
                    } elseif ($rating == 'dislike') {
                        $rating = -1;
                    } else {
                        $rating = 0;
                    }

                    $newVote->vote = $rating;
                    $resultVote = $newVote->save();
                    $newVote->save();

                    if ($resultVote) {
                        $result = [
                            'success' => true,
                            'message' => 'Ваш голос учтен',
                        ];

                    } else {
                        $result = [
                            'success' => false,
                            'message' => 'Что то пошло не так',
                        ];
                    }
                } else {
                    $result = [
                        'success' => false,
                        'message' => 'Голосовать можно раз в сутки',
                    ];
                }
            } else {
                $result = [
                    'success' => false,
                    'message' => 'Значение кнопки не известно'
                ];
            }
            return json_encode($result);
        } else {
            $result = [
                'error' => false,
                'message' => 'Не ajax'
            ];
            return json_encode($result);
        }
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        $exceptionMessage = $exception->getMessage();
        $status = $exception->statusCode;
//        var_dump($exception->statusCode);
        //TODO: Передавать код ошибки
        return $this->render(Yii::getAlias('/page-templates/default/error.twig'),
            [
                'exceptionMessage' => $exceptionMessage,
                'exception' => $exception,
                'status' => $status,
            ]);
    }

}
