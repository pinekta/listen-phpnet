<?php

$inputFilePath = "dest/wav";
$outputDir = "dest/union";

try {
    exec("ls {$inputFilePath}", $inputFiles);
    natsort($inputFiles);
    foreach ($inputFiles as $input) {
        $articleNumber = substr($input, 0, strpos($input, '_'));
        if ($articleNumber !== $prevNumber) {
            $inputFiles = implode(" ", $inFiles);
            // ファイルを結合
            exec("sox {$inputFiles} {$outputDir}/{$prevNumber}.wav", $output);
            unset($inFiles);
        }
        $inFiles[] = "{$inputFilePath}/{$input}";
        $prevNumber = $articleNumber;
    }
    $inputFiles = implode(" ", $inFiles);
    // ファイルを結合
    exec("sox {$inputFiles} {$outputDir}/{$prevNumber}.wav", $output);
    unset($inFiles);
} catch (Exception $e) {
    print_r($e->getMessage());
    print_r($output);
}
