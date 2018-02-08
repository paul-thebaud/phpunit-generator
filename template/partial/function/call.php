<?php

//
// Render the function call depending on function
//

$paramsAnnotation = $function->getParamsAnnotation();
$parameters = $paramsAnnotation !== null ? $paramsAnnotation->getParameters() : [];
$parameters = $this->getAttribute('parametersHelper')->invoke($parameters);

// Global function
if ($function->isGlobal()) {
    $namespace = $function->getParentNode()->getNamespaceString();
    $namespace = ($namespace === null? '' : '\\' . $namespace . '\\');
    echo sprintf(
        "%s\$result = %s%s(%s);",
        str_repeat(' ', 8),
        $namespace,
        $function->getName(),
        $parameters
    );
} else {
    // Private or protected method
    if (! $function->isPublic()) {
        echo sprintf(
            "%s\$method = (new \ReflectionClass(%s::class))\n%s->getMethod('%s');\n%s\$method->setAccessible(true);\n",
            str_repeat(' ', 8),
            $function->getParentNode()->getName(),
            str_repeat(' ', 12),
            $function->getName(),
            str_repeat(' ', 8)
        );
        $objectToUse = $function->isStatic() ? 'null' : ('$this->' . lcfirst($function->getParentNode()->getName()));
        $parameters = strlen($parameters) > 0 ? ', ' . $parameters : '';
        echo sprintf(
            "%s\$result = \$method->invoke(%s%s);",
            str_repeat(' ', 8),
            $objectToUse,
            $parameters
        );
    } else {
        // Public static method
        if ($function->isStatic()) {
            echo sprintf(
                "%s\$result = %s::%s(%s);",
                str_repeat(' ', 8),
                $function->getParentNode()->getName(),
                $function->getName(),
                $parameters
            );
        } else {
            // Public not static
            echo sprintf(
                "%s\$result = %s->%s(%s);",
                str_repeat(' ', 8),
                '$this->' . lcfirst($function->getParentNode()->getName()),
                $function->getName(),
                $parameters
            );
        }
    }
} ?>

