# listen-phpnet

php.netのマニュアルを音声ファイル化します。

## 推奨環境

ubuntu 14.04.2 LTSもしくはそれ以上のバージョン

## 環境構築

```
sudo apt-get install -y open-jtalk open-jtalk-mecab-naist-jdic libhtsengine1 htsengine
wget http://kr.archive.ubuntu.com/ubuntu/pool/multiverse/h/hts-voice-nitech-jp-atr503-m001/hts-voice-nitech-jp-atr503-m001_1.05-1_all.deb
sudo dpkg -i hts-voice-nitech-jp-atr503-m001_1.05-1_all.deb
sudo apt-get install -y alsa-utils unzip
sudo apt-get install -y sox
```

バージョンを確認します。

```
vagrant@vagrant-ubuntu-trusty-64:~$ open_jtalk
The Japanese TTS System "Open JTalk"
Version 1.07 (http://open-jtalk.sourceforge.net/)
Copyright (C) 2008-2013 Nagoya Institute of Technology
All rights reserved.

The HMM-Based Speech Synthesis Engine "hts_engine API"
Version 1.08 (http://hts-engine.sourceforge.net/)
Copyright (C) 2001-2013 Nagoya Institute of Technology
              2001-2008 Tokyo Institute of Technology
All rights reserved.

Yet Another Part-of-Speech and Morphological Analyzer "Mecab"
Version 0.994 (http://mecab.sourceforge.net/)
Copyright (C) 2001-2008 Taku Kudo
              2004-2008 Nippon Telegraph and Telephone Corporation
All rights reserved.

NAIST Japanese Dictionary
Version 0.6.1-20090630 (http://naist-jdic.sourceforge.jp/)
Copyright (C) 2009 Nara Institute of Science and Technology
All rights reserved.

open_jtalk - The Japanese TTS system "Open JTalk"
```

次に女声のインストールを行います。  
sourceforgeからダウンロードします。
[sourceforge MMDAgent](https://sourceforge.net/projects/mmdagent/)

```
unzip MMDAgent_Example-1.4.zip
```

解凍したディレクトリに移動し、htsvoiceディレクトリにコピーします。

```
cd MMDAgent_Example-1.4/Voice
sudo cp -R mei /usr/share/hts-voice
```

最後にcomposer.jsonに定義されているライブラリをインストールします。  
composer.pharをダウンロードし、以下のコマンドを実行します。
```
$ php composer.phar install
```

## 音声ファイルの作成

```
$ php scrape2Wav.php
$ php combineWavBySox.php
```

音声ファイルが`dest/combined`に作成されます。

## Copyright

Copyright 2017 pinekta
twitter:@pinekta

License: MIT
