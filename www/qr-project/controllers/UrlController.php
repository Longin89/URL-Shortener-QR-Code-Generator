<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Url;
use app\models\UrlLog;
use app\models\UrlForm;

/**
 * Контроллер для работы с короткими ссылками
 */
class UrlController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $enableCsrfValidation = false; // Отключаем CSRF для Ajax запросов

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Ajax создание короткой ссылки
     * @return array
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new UrlForm();

        // Читаем JSON из тела
        $jsonData = json_decode(Yii::$app->request->rawBody, true);
        // Загружаем данные из JSON
        $model->load($jsonData, '');

        // Создаем короткую ссылку и возвращаем результат
        if ($url = $model->createShortUrl()) {
            return [
                'success' => true,
                'data' => [
                    'short_url' => $url->getShortUrl(),
                    'qr_code' => $url->qr_code,
                    'original_url' => $url->original_url,
                    'short_code' => $url->short_code,
                    'hits' => $url->hits,
                ],
            ];
        }

        return [
            'success' => false,
            'errors' => $model->errors,
            'message' => current($model->getFirstErrors()),
        ];
    }

    /**
     * Редирект по короткой ссылке
     * @param string $code
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionRedirect($code)
    {
        $url = Url::findOne(['short_code' => $code]);

        if ($url === null) {
            throw new NotFoundHttpException('Короткая ссылка не найдена');
        }

        // Увеличиваем счетчик
        $url->incrementHits();

        // Логируем переход
        UrlLog::logVisit(
            $url->id,
            Yii::$app->request->userIP,
            Yii::$app->request->userAgent
        );

        // Редиректим на оригинальный URL
        return $this->redirect($url->original_url);
    }
}
