<?php

namespace frontend\controllers;

use common\models\Categories;
use common\models\LoginForm;
use common\models\Video;
use common\models\Votes;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex($cat)
    {
        if (!empty($cat)) {
            $video_list = Video::find()->joinWith('directoryStatus')->joinWith('categories')->orderBy(new Expression('rand()'))->where(['directory_statuses.value' => '1'])->andWhere(['categories.code' => $cat])->one();
        } else {
            $video_list = Video::find()->joinWith('directoryStatus')->orderBy(new Expression('rand()'))->where(['directory_statuses.value' => '1'])->one();
        }
        if ($video_list['views'] !== null) {
            $video_list->updateCounters(['views' => 1]);
        }
        return $this->render('index', ['video' => $video_list]);
    }

    public function actionAdd($cat)
    {
        return $this->render('add');
    }

    public function actionAddSend()
    {
        if (Yii::$app->request->isAjax) {
            $arrayNames = Yii::$app->request->post('name_video');
            $arrayLinks = Yii::$app->request->post('link_video');
            $categories = Yii::$app->request->referrer;
            $categories = parse_url($categories);

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
                    if ($categories['path'] == '/c/music/add/') {
                        $cat_id = Categories::find()->asArray()->select('id')->where(['code' => 'music'])->one();
                        $video->category_id = $cat_id['id'];
                    } elseif ($categories['path'] == '/c/auto/add/') {
                        $cat_id = Categories::find()->asArray()->select('id')->where(['code' => 'auto'])->one();
                        $video->category_id = $cat_id['id'];
                    }
                    $video->save();
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
            $referer = parse_url(Yii::$app->request->referrer);
            if ($referer['path'] == '/c/auto/') {
                $category = 'auto';
                $newSrc = Video::find()->joinWith('directoryStatus')->joinWith('categories')->orderBy(new Expression('rand()'))
                    ->where(['not', ['link_video' => $src]])->andWhere(['directory_statuses.value' => '1'])->andWhere(['categories.code' => $category])->one();
            } elseif ($referer['path'] == '/c/music/') {
                $category = 'music';
                $newSrc = Video::find()->joinWith('directoryStatus')->joinWith('categories')->orderBy(new Expression('rand()'))
                    ->where(['not', ['link_video' => $src]])->andWhere(['directory_statuses.value' => '1'])->andWhere(['categories.code' => $category])->one();
            } else {
                $newSrc = Video::find()->joinWith('directoryStatus')->orderBy(new Expression('rand()'))->where(['not', ['link_video' => $src]])->andWhere(['directory_statuses.value' => '1'])->one();
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

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
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

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
