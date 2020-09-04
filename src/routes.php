<?php
// Существующие роуты в приложении
return [
    // главная страница с текстом задания
    '/' => App\Controller\Home::class,
    // Страница с формой
    '/feedback' => App\Controller\Feedback::class,
    // для ajax запроса на сохранение данных с формы feedback
    '/store' => App\Controller\Feedback::class.'@store',
    // Форма для подгрузки сообщений из базы
    '/feedback-list' => App\Controller\FeedbackList::class,
    '/feedback-pages' => App\Controller\FeedbackList::class.'@pages',
    '/feedback-get' => App\Controller\FeedbackList::class.'@get',
];
