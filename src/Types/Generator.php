<?php

namespace Types;

use Carbon\Carbon;
use Cmixin\BusinessDay;
use ReflectionClass;
use ReflectionParameter;

class Generator
{
    /**
     * @param callable|null $boot
     *
     * @throws \ReflectionException
     *
     * @return mixed
     */
    protected function getMethods($boot)
    {
        call_user_func($boot ?: function () {
            BusinessDay::enable('\Carbon\Carbon');
        });

        $c = new ReflectionClass(Carbon::now());
        $macros = $c->getProperty('globalMacros');
        $macros->setAccessible(true);

        return $macros->getValue();
    }

    /**
     * @param callable|null $boot
     * @param string        $source
     *
     * @throws \ReflectionException
     *
     * @return string
     */
    protected function getMethodsDefinitions($boot, $source)
    {
        $methods = '';
        $source = str_replace('\\', '/', realpath($source));
        $sourceLength = strlen($source);

        foreach ($this->getMethods($boot) as $name => $closure) {
            try {
                $function = new \ReflectionFunction($closure);
            } catch (\ReflectionException $e) {
                continue;
            }

            $file = str_replace('\\', '/', $function->getFileName());

            if (substr($file, 0, $sourceLength + 1) !== "$source/") {
                continue;
            }

            $file = substr($file, $sourceLength + 1);

            $parameters = implode(', ', array_map(array($this, 'dumpParameter'), $function->getParameters()));
            $methodDocBlock = trim($function->getDocComment() ?: '');
            $className = '\\'.str_replace('/', '\\', substr($file, 0, -4));
            $file .= ':'.$function->getStartLine();

            if ($methods !== '') {
                $methods .= "\n";
            }

            if ($methodDocBlock !== '') {
                $methodDocBlock = str_replace('/**', "/**\n         * @see $className::$name\n         *", $methodDocBlock);
                $methods .= "        $methodDocBlock\n";
            }

            $methods .= "        public static function $name($parameters)\n".
                "        {\n".
                "            // Content, see src/$file\n".
                "        }\n";
        }

        return $methods;
    }

    /**
     * @param string   $source
     * @param string   $destination
     * @param callable $boot
     * @param string   $name
     *
     * @throws \ReflectionException
     */
    public function writeHelpers($source, $destination, $name = '_ide_business_day', callable $boot = null, array $classes = null)
    {
        $methods = $this->getMethodsDefinitions($boot, $source);

        $classes = $classes ?: array(
            'Carbon\Carbon',
            'Carbon\CarbonImmutable',
            'Illuminate\Support\Carbon',
        );

        $code = "<?php\n";

        foreach ($classes as $class) {
            $class = explode('\\', $class);
            $className = array_pop($class);
            $namespace = implode('\\', $class);
            $code .= "\nnamespace $namespace\n{\n    class $className\n    {\n$methods    }\n}\n";
        }

        file_put_contents("$destination/{$name}_static.php", $code);
        file_put_contents("$destination/{$name}_instantiated.php", str_replace(
            "\n        public static function ",
            "\n        public function ",
            $code
        ));
    }

    protected function dumpValue($value)
    {
        if ($value === null) {
            return 'null';
        }

        $value = preg_replace('/^array\s*\(\s*\)$/', '[]', var_export($value, true));
        $value = preg_replace('/^array\s*\(([\s\S]+)\)$/', '[$1]', $value);

        return $value;
    }

    protected function dumpParameter(ReflectionParameter $parameter)
    {
        $name = $parameter->getName();
        $output = '$'.$name;

        if ($parameter->isVariadic()) {
            $output = "...$output";
        }

        if ($parameter->getType()) {
            $name = $parameter->getType()->getName();
            if (preg_match('/^[A-Z]/', $name)) {
                $name = "\\$name";
            }
            $name = preg_replace('/^\\\\Carbon\\\\/', '', $name);
            $output = "$name $output";
        }

        try {
            if ($parameter->isDefaultValueAvailable()) {
                $output .= ' = '.$this->dumpValue($parameter->getDefaultValue());
            }
        } catch (\ReflectionException $exp) {
        }

        return $output;
    }
}
