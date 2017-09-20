<?php

require_once 'vendor/autoload.php';
require_once 'config/pages.php';

use Goutte\Client;

$voicePath = "/usr/share/hts-voice/mei";
$voiceType = "mei_normal.htsvoice";
$dictPath = "/var/lib/mecab/dic/open-jtalk/naist-jdic";
$destTxtPath = './dest/txt';
$destWavPath = './dest/wav';

$maxBuffer = 1024;
$pageCount = 0;

foreach ($pages as $pageName) {
    $pageCount++;
    $client = new Client();
    try {
        $crawler = $client->request('GET', "http://php.net/manual/ja/{$pageName}.php");

        // 音声変換する記事を配列に格納していく
        try {
            $news[] = $crawler->filter('h2.title')->first()->text();
        } catch (\InvalidArgumentException $e) {

        }
        try {
            $news[] = $crawler->filter('p.simpara')->first()->text();
        } catch (\InvalidArgumentException $e) {

        }
        try {
            $crawler->filter('p.para')->each(function ($element) use (&$news) {
                $news[] = $element->text();
            });
        } catch (\InvalidArgumentException $e) {

        }
    } catch (\Exception $e) {
        echo "pageName: {$pageName} スクレイピング処理でエラーが発生しました。";
        throw $e;
    }

    try {
        // 音声に変換していく
        $sentences = "";
        $outputUnits = [];
        foreach ($news as $sentence) {
            // TODO $sentence自体が1024バイト超える場合は？
            // 指定バイト数を超えないように配列に格納する
            if (strlen($sentences . $sentence) >= $maxBuffer) {
                $outputUnits[] = $sentences;
                $sentences = "";
            }
            $sentences .= trim(str_replace("    ", "", $sentence));
        }
        $outputUnits[] = $sentences;
        
        $outputUnitsCount = count($outputUnits);
        for ($i = 0; $i < $outputUnitsCount; $i++) {
            $outputContext = str_replace("\n", "", $outputUnits[$i]);
			//exec("echo {$outputContext} | open_jtalk -m {$voicePath}/{$voiceType} -ow {$pageName}_{$i}.wav -x {$dictPath}", $output);
			file_put_contents("{$destTxtPath}/{$pageCount}_{$pageName}_{$i}.txt", $outputContext);
			exec("open_jtalk -m {$voicePath}/{$voiceType} -ow {$destWavPath}/{$pageCount}_{$pageName}_{$i}.wav -x {$dictPath} {$destTxtPath}/{$pageCount}_{$pageName}_{$i}.txt", $output);
        }
        unset($client, $crawler, $news, $outputUnits);
    } catch (\Exception $e) {
        echo "pageName: {$pageName} 音声変換処理でエラーが発生しました。";
        throw $e;
    }
}
