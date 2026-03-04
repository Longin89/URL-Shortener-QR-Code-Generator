<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\UrlForm $model */

$this->title = 'Сервис коротких ссылок';
?>

<div class="url-index">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h1 class="text-center mb-4">
                            <i class="bi bi-link-45deg"></i> <?= Html::encode($this->title) ?>
                        </h1>

                        <p class="text-center text-muted mb-4">
                            Протестируйте приложение и возьмите меня на работу
                        </p>

                        <div id="url-form">
                            <div class="input-group mb-3">
                                <?= Html::input('text', 'url', '', [
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'url-input',
                                    'placeholder' => 'Введите URL (например: https://google.com)',
                                    'autocomplete' => 'off',
                                ]) ?>
                                <button class="btn btn-primary btn-lg" type="button" id="create-btn">
                                    <i class="bi bi-check-lg"></i> OK
                                </button>
                            </div>

                            <div id="error-message" class="alert alert-danger d-none" role="alert"></div>
                            <div id="loading" class="text-center d-none">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Загрузка...</span>
                                </div>
                                <p class="mt-2">Проверка и создание короткой ссылки...</p>
                            </div>
                        </div>

                        <div id="result" class="d-none mt-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title text-center mb-3">
                                        <i class="bi bi-check-circle-fill text-success"></i> Готово!
                                    </h5>

                                    <div class="row">
                                        <div class="col-md-6 text-center mb-3">
                                            <div class="qr-code-container">
                                                <img id="qr-code" src="" alt="QR Code" class="img-fluid mb-2" style="max-width: 200px;">
                                                <p class="small text-muted">Наведите камеру телефона</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Короткая ссылка:</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="#" id="short-url" class="text-decoration-none fs-5 fw-bold text-primary" target="_blank"></a>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Оригинальная ссылка:</label>
                                                <input type="text" id="original-url" class="form-control" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Статистика:</label>
                                                <p class="mb-0">Переходов: <span id="hits-count" class="badge bg-primary">0</span></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-3">
                                        <button class="btn btn-success" id="create-another-btn">
                                            <i class="bi bi-plus-circle"></i> Создать еще одну ссылку
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>