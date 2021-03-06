<?php
// Существующие роуты в приложении
return [
    // главная страница с текстом задания
    '/' => App\Controller\Home::class,
    // Страница с формой
    '/feedback' => App\Controller\Feedback::class,
    // страница с формой редактирования
    '/feedback/edit-form' => App\Controller\Feedback::class.'@editForm',
    // для ajax запроса на сохранение данных с формы feedback
    '/feedback/store' => App\Controller\FeedbackAjax::class.'@store',
    // для ajax запроса на получение feedback сообщения
    '/feedback/get' => App\Controller\FeedbackAjax::class.'@get',
    // для ajax запроса на удаление feedback сообщения
    '/feedback/delete' => App\Controller\FeedbackAjax::class.'@delete',

    // Форма список сообщений из базы
    '/feedback-list' => App\Controller\FeedbackList::class,
    // для ajax запросов получения количества страницы
    '/feedback-list/pages' => App\Controller\FeedbackListAjax::class.'@pages',
    // для ajax запроса получения сообщений на конкретной странице
    '/feedback-list/get' => App\Controller\FeedbackListAjax::class.'@get',
];
