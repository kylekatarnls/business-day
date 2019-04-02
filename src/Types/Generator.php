<?php

namespace Types;

use Carbon\Carbon;
use Cmixin\BusinessDay;
use ReflectionClass;
use ReflectionParameter;

class Generator
{
    /**
     * @throws \ReflectionException
     *
     * @return mixed
     */
    protected function getMethods()
    {
        BusinessDay::enable('\Carbon\Carbon');

        $c = new ReflectionClass(Carbon::now());
        $macros = $c->getProperty('globalMacros');
        $macros->setAccessible(true);

        return $macros->getValue();
    }

    /**
     * @throws \ReflectionException
     *
     * @return string
     */
    protected function getMethodsDefinitions($source)
    {
        $methods = '';
        $source = str_replace('\\', '/', realpath($source));
        $sourceLength = strlen($source);

        foreach ($this->getMethods() as $name => $closure) {
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
     * @param string $source
     * @param string $destination
     *
     * @throws \ReflectionException
     */
    public function writeHelpers($source, $destination)
    {
        $methods = $this->getMethodsDefinitions($source);

        $code = "<?php\n\n".
            "namespace Carbon\n{\n    class Carbon\n    {\n$methods    }\n}\n\n".
            "namespace Carbon\n{\n    class CarbonImmutable\n    {\n$methods    }\n}\n\n".
            "namespace Illuminate\Support\n{\n    class Carbon\n    {\n$methods    }\n}\n";

        file_put_contents("$destination/_ide_business_day_static.php", $code);
        file_put_contents("$destination/_ide_business_day_instantiated.php", str_replace(
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
