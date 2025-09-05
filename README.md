# ModxRevolutionFileEventsPlugin
Plugin for running files corresponding to the event name. Idea - is designed to simplify the management of plugins through version control systems and provide low coupling with the database for storing code

## Start

1. Create modx plugin with code from `plugin.php`
2. Select Events in admin panel for plugin
3. Place event files in `core/elements/events/<EventName>/*.php`

## Example of event File

```php
<?php
/** @var modX $modx */
/** @var modSystemEvent $event */
/** @var array $scriptProperties */
/** @var mixed $fenom */ // if possible

// Add comment to page content
if (isset($modx->resource) && isset($modx->resource->_output)) {
    $modx->resource->_output .= "\n<!-- handled by events: {$event->name} -->";
}

// Can return string to $event->output()
return '';
```
