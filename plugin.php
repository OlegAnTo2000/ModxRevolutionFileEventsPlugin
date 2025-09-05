<?php
/**
 * Description: Include core/elements/events/<EventName>.php if exist.
 */

/** @var modX $modx */
$eventName = $modx->event->name ?? '';
if (!$eventName) return;

$baseDir = rtrim(MODX_CORE_PATH, '/\\') . '/elements/events/';
$file    = $baseDir . $eventName . '.php';

if (!is_file($file) || !is_readable($file)) {
    $modx->log(modX::LOG_LEVEL_ERROR, "[EventsRouterSimple] Файл для события {$eventName} не найден: {$file}");
    return;
}

$event = $modx->event;

try {
    $result = include $file;
    if (is_string($result) && $result !== '') {
        $event->output($result);
    }
} catch (Throwable $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, "[EventsRouterSimple] Ошибка в {$eventName}: {$e->getMessage()}");
}
