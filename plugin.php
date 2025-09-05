<?php
/**
 * Description: Execute all PHP handlers from core/elements/events/<EventName>/*.php
 */

/** @var modX $modx */
$eventName = $modx->event->name ?? '';
if (!$eventName) return;

// Resolve directory for the current event
$eventDir = rtrim(MODX_CORE_PATH, '/\\') . '/elements/events/' . $eventName . '/';

// If directory is missing — log only on DEBUG and exit
if (!is_dir($eventDir)) {
    $modx->log(modX::LOG_LEVEL_DEBUG, "[EventsRouterDirSimple] Event directory not found for {$eventName}: {$eventDir}");
    return;
}

// Collect all PHP handlers in alphabetical order
$files = glob($eventDir . '*.php') ?: [];
if (!$files) {
    $modx->log(modX::LOG_LEVEL_DEBUG, "[EventsRouterDirSimple] No handlers (*.php) for {$eventName} in {$eventDir}");
    return;
}
sort($files, SORT_STRING);

$event = $modx->event;

// Execute each handler; do not stop on errors
foreach ($files as $php) {
    try {
        // Handlers see $modx, $event, $scriptProperties and any third-party vars (e.g., $fenom) already present
        $ret = include $php;

        // If a handler returns a non-empty string — send it to event output
        if (is_string($ret) && $ret !== '') {
            $event->output($ret);
        }
    } catch (Throwable $e) {
        // Log error and continue to the next handler
        $modx->log(modX::LOG_LEVEL_ERROR, "[EventsRouterDirSimple] Error in {$eventName} -> {$php}: " . $e->getMessage());
    }
}
