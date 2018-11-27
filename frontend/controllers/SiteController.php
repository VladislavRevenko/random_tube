<?php

namespace frontend\controllers;

use common\models\Categories;
use common\models\LoginForm;
use common\models\Video;
use common\models\Votes;
use frontend\models\SignupForm;
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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex($cat)
    {
        if (!empty($cat)) {
            $searchCat = Categories::find()->where(['code' => $cat])->one();
            if ($searchCat == null) {
                throw new HttpException(404, 'Категории не существует');
            }
            $video_list = Video::find()->joinWith('directoryStatus')->joinWith('categories')->orderBy(new Expression('rand()'))->where(['directory_statuses.value' => '1'])->andWhere(['categories.code' => $cat])->one();
        } else {
            $video_list = Video::find()->joinWith('directoryStatus')->orderBy(new Expression('rand()'))->where(['directory_statuses.value' => '1'])->andWhere(['category_id' => null])->one();
        }
        if ($video_list['views'] !== null) {
            $video_list->updateCounters(['views' => 1]);
        }
        return $this->render('index', ['video' => $video_list]);
    }

    public function actionAdd($cat)
    {
        $category = Categories::find()->asArray()->select('id')->where(['code' => $cat])->one();
        if ($category == null && $cat != '') {
            throw new HttpException(404, 'Категории не существует');
        }
        return $this->render('add');
    }

    public function actionAddSend()
    {
        if (Yii::$app->request->isAjax) {
            $arrayNames = Yii::$app->request->post('name_video');
            $arrayLinks = Yii::$app->request->post('link_video');
            $category = Yii::$app->request->post('category');

            if (count($arrayNames) != count($arrayLinks)) {
                $result = [
                    'status' => 'error',
                    'message' => 'Не соответствующий формат ссылок ' . count($arrayNames) . ' ' . count($arrayLinks),
                ];
                return json_encode($result);
            }

            $errors = array();

            $allowedHosts = array(
                "youtube.com",
                "www.youtube.com",
                "youtu.be",
            );

            for ($count = 0; $count < count($arrayNames); $count++) {
                $video = new Video();
                $parseLink = parse_url($arrayLinks[$count]);
                if ($parseLink !== '' && $parseLink !== null && !empty($parseLink['host'])) {
                    if (array_search($parseLink['host'], $allowedHosts) == false) {
                        $result = [
                            'status' => 'error',
                            'message' => 'Не соответствующий формат ссылок',
                        ];
                        return json_encode($result);
                    }
                    $video->link_video = $arrayLinks[$count];
                    $video->name = $arrayNames[$count];
                    if ($category != '') {
                        $categoryId = Categories::find()->asArray()->where(['code' => $category])->one();
                        $video->category_id = $categoryId['id'];
                    }

                    if (!$video->save()) {
                        $errors[] = $video->link_video;
                    }

                } else {
                    $result = [
                        'status' => 'error',
                        'message' => 'Не соответствующий формат ссылок',
                    ];
                    return json_encode($result);
                }
            }
            $result = [
                'status' => 'success',
                'message' => 'Заявка отправлена',
                'errors' => $errors,
            ];
            return json_encode($result);
        } else {
            $result = [
                'status' => 'error',
                'message' => 'no is AJAX',
            ];
            return json_encode($result);
        }
    }

    public function actionGetVideo()
    {
        if (Yii::$app->request->isAjax) {
            $src = Yii::$app->request->post('srcVideo');
            $cat = Yii::$app->request->post('catVideo');
            if (!empty($cat)) {
                $newSrc = Video::find()->joinWith('directoryStatus')->joinWith('categories')->orderBy(new Expression('rand()'))
                    ->where(['not', ['link_video' => $src]])->andWhere(['directory_statuses.value' => '1'])->andWhere(['categories.code' => $cat])->one();
            } else {
                $newSrc = Video::find()->joinWith('directoryStatus')->orderBy(new Expression('rand()'))->where(['not', ['link_video' => $src]])->andWhere(['directory_statuses.value' => '1'])->andWhere(['category_id' => null])->one();
            }
            if (is_object($newSrc)) {
                $src = $newSrc->link_video;
                $newSrc->updateCounters(['views' => 1]);
            }

            $result = [
                'status' => 'success',
                'message' => 'Успешно',
                'newSrc' => $src,
            ];
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
            $date = Yii::$app->request->post('date');
            $srcVideo = Yii::$app->request->post('video');

            if (!empty($rating)) {
                if ($date) {
                    $arVideo = Video::find()->where(['link_video' => $srcVideo])->one();
                    if (is_object($arVideo)) {
                        $vote = Votes::find()->where(['video_id' => $arVideo->id, 'ip' => $_SERVER['REMOTE_ADDR']])->asArray()->one();
                    } else {
                        $result = [
                            'success' => false,
                            'message' => 'Такого видео нет.',
                        ];
                        return json_encode($result);
                    }

                    $parseDateNow = strtotime($date);
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
                        $newVote->ip = $_SERVER['REMOTE_ADDR'];
                        $newVote->useragent = $_SERVER['HTTP_USER_AGENT'];

                        if ($rating == 'like') {
                            $rating = 1;
                        } elseif ($rating == 'dislike') {
                            $rating = -1;
                        } else {
                            $rating = 0;
                        }

                        $newVote->vote = $rating;
                        $newVote->created = $date;
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
                        'message' => 'Время не известно',
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

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

}
