<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerTTnNOBW\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerTTnNOBW/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerTTnNOBW.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerTTnNOBW\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerTTnNOBW\App_KernelDevDebugContainer([
    'container.build_hash' => 'TTnNOBW',
    'container.build_id' => 'aa7b751f',
    'container.build_time' => 1717759397,
    'container.runtime_mode' => \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? 'web=0' : 'web=1',
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerTTnNOBW');
