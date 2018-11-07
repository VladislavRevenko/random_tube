<?php

namespace frontend\controllers;

use common\models\Video;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Security;
use yii\db\Expression;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

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
    public function actionIndex()
    {
        $video_list = Video::find()->orderBy(new Expression('rand()'))->where(['status' => 'active'])->one();
        return $this->render('index', ['video' => $video_list]);
    }

    public function actionAdd()
    {
        return $this->render('add');
    }

    public function actionAddSend()
    {
        if (Yii::$app->request->isAjax) {
            $dataForm = Yii::$app->request->post('linkVideo');
            foreach ($dataForm as $item) {
                $url_host = parse_url($item);
                if ($item !== '' && $item !== null && !empty($url_host['host'])) {
                    if ($url_host['host'] == 'www.youtube.com') {
                        $url_video = preg_replace('/v=/', '', $url_host['query']);
                        $video = new Video();
                        $video->link_video = "https://www.youtube.com/embed/" . $url_video;
                        $video->save();
                    } elseif ($url_host['host'] == 'youtu.be') {
                        $url_video = preg_replace('/https:\/\/youtu.be\//', '', $item);
                        $video = new Video();
                        $video->link_video = "https://www.youtube.com/embed/" . $url_video;
                        $video->save();
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
                'data' => $dataForm
            ];
        } else {
            $result = [
                'status' => 'error',
                'message' => 'no is AJAX',
            ];
        }
        return json_encode($result);
    }


    public function actionButtonVideo()
    {
        if (Yii::$app->request->isAjax) {
            $button = Yii::$app->request->post('idButton');
            $src = Yii::$app->request->post('srcVideo');

            $video = Video::find()->where(['link_video' => $src])->one();
            if ($button == 'like') {
                $video->like = $video->like + 1;
                $video->save();
            } elseif ($button == 'dislike') {
                $video->dislike = $video->dislike + 1;
                $video->save();
            } elseif ($button == 'still') {
                $newSrc = Video::find()->orderBy(new Expression('rand()'))->where(['not', ['link_video' => $src]])->andWhere(['status' => 'active'])->one();
                $src = $newSrc->link_video;
            }

            $result = [
                'status' => 'success',
                'message' => 'Успешно',
                'newSrc' => $src
            ];
        } else {
            $result = [
                'status' => 'error',
                'message' => 'no is AJAX',
            ];
        };
        return json_encode($result);
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
