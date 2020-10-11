<?php

namespace Types;

use Carbon\Carbon;
use Cmixin\BusinessDay;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;

class Generator
{
    /**
     * @param callable|null $boot
     *
     * @throws ReflectionException
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
     * @param string        $defaultClass
     *
     * @throws ReflectionException
     *
     * @return string
     */
    protected function getMethodsDefinitions($boot, $source, $defaultClass)
    {
        $methods = '';
        $source = str_replace('\\', '/', realpath($source));
        $sourceLength = strlen($source);
        $files = [];

        foreach ($this->getMethods($boot) as $name => $closure) {
            try {
                $function = new ReflectionFunction($closure);
            } catch (ReflectionException $e) {
                continue;
            }

            $file = $function->getFileName();

            if (!isset($files[$file])) {
                $files[$file] = file($file);
            }

            $lines = $files[$file];
            $file = str_replace('\\', '/', $file);

            if (substr($file, 0, $sourceLength + 1) !== "$source/") {
                continue;
            }

            $file = substr($file, $sourceLength + 1);
            $parameters = implode(', ', array_map([$this, 'dumpParameter'], $function->getParameters()));
            $methodDocBlock = trim($function->getDocComment() ?: '');
            $length = $function->getStartLine() - 1;
            $code = array_slice($lines, 0, $length);
            $className = '\\'.str_replace('/', '\\', substr($file, 0, -4));

            for ($i = $length - 1; $i >= 0; $i--) {
                if (preg_match('/^\s*(public|protected)\s+function\s+(\S+)\(.*\)(\s*\{)?$/', $code[$i], $match)) {
                    if ($name !== $match[2]) {
                        try {
                            $method = new ReflectionMethod($className, $name);
                        } catch (ReflectionException $e) {
                            $method = new ReflectionMethod($defaultClass, $name);
                        }

                        $methodFile = $method->getFileName();

                        if (!isset($files[$methodFile])) {
                            $files[$methodFile] = file($methodFile);
                        }

                        $length = $method->getEndLine() - 1;
                        $lines = $files[$methodFile];

                        if ($length > 3 && preg_match('/^\s*\*+\/\s*$/', $lines[$length - 2])) {
                            $doc = '';

                            for ($i = $length - 2; $i >= max(0, $length - 42); $i--) {
                                $doc = $lines[$i].$doc;

                                if (preg_match('/\s*\/\*{2,}\s*/', $lines[$i])) {
                                    $methodDocBlock = trim($doc);

                                    break;
                                }
                            }
                        }

                        $code = array_slice($lines, 0, $length);

                        for ($i = $length - 1; $i >= 0; $i--) {
                            if (preg_match('/^\s*(public|protected)\s+function\s+(\S+)\(.*\)(\s*\{)?$/', $code[$i], $match)) {
                                break;
                            }
                        }

                        $code = implode('', array_slice($code, $i));

                        if (preg_match('/(\/\*\*[\s\S]+\*\/)\s+return\s/U', $code, $match)) {
                            $methodDocBlock = $match[1];
                        }
                    }

                    break;
                }
            }

            $methodDocBlock = preg_replace('/^ +\*/m', '         *', $methodDocBlock);
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
     * @param string   $defaultClass
     * @param string   $source
     * @param string   $destination
     * @param callable $boot
     * @param string   $name
     *
     * @throws ReflectionException
     */
    public function writeHelpers($defaultClass, $source, $destination, $name = '_ide_business_day', callable $boot = null, array $classes = null)
    {
        $methods = $this->getMethodsDefinitions($boot, $source, $defaultClass);

        $classes = $classes ?: [
            'Carbon\Carbon',
            'Carbon\CarbonImmutable',
            'Illuminate\Support\Carbon',
            'Illuminate\Support\Facades\Date',
        ];

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
        } catch (ReflectionException $exp) {
        }

        return $output;
    }
}
