<?php

$keys = array();
foreach (glob('src/Cmixin/Holidays/*.php') as $file) {
    $keys = array_merge($keys, include $file);
}
$keys = array_keys($keys);
$length = max(array_map('strlen', $keys));
sort($keys);

foreach (glob('src/Cmixin/HolidayNames/*.php') as $file) {
    $data = include $file;
    $newData = array();

    foreach ($keys as $key) {
        $newData[$key] = isset($data[$key]) ? $data[$key] : 'Unknown';
    }

    if (true || $newData !== $data) {
        $data = str_replace('array(', 'array(', var_export($newData, true));
        $data = preg_replace_callback('/^\s*\'([^\']+)\'\s*=>/m', function ($match) use ($length) {
            $key = $match[1];
            $spaces = str_repeat(' ', $length - strlen($key));

            return "    '$key'$spaces =>";
        }, $data);
        file_put_contents($file, "<?php\n\nreturn $data;\n");
    }
}
