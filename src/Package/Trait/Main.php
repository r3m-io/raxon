<?php
namespace Package\R3m\Io\Raxon\Trait;

use R3m\Io\Config;

use R3m\Io\Module\Core;
use R3m\Io\Module\File;
use R3m\Io\Module\Dir;

use Exception;

trait Main {

    /**
     * @throws Exception
     */
    public function compile($options=[]): void
    {
        $object = $this->object();
        $posix_id = $object->config(Config::POSIX_ID);
        if(
            !in_array(
                $posix_id,
                [
                    0,
                    33
                ]
            )
        ){
            throw new Exception('Access denied...');
        }
        ddd($options);
    }

    /**
     * @throws Exception
     */
    public function ast($options): void
    {
        $object = $this->object();
        $posix_id = $object->config(Config::POSIX_ID);

        if(
            !in_array(
                $posix_id,
                [
                    0,
                    33
                ]
            )
        ){
            throw new Exception('Access denied...');
        }
        if(property_exists($options, 'document') === false){
            throw new Exception('Document not found...');
        }
        if(property_exists($options, 'data') === false){
            $data = [];
        }
        elseif(File::exist($options->data)){
            $data = Core::object(File::read($options->data), Core::OBJECT_OBJECT);
            ddd($data);
        }

        $read = File::read($options->document);
        ddd($options);
    }

    /**
     * @throws Exception
     */
    public function inotify($options): void
    {
        $object = $this->object();
        $posix_id = $object->config(Config::POSIX_ID);
        if(
            !in_array(
                $posix_id,
                [
                    0,
                    33
                ]
            )
        ){
            throw new Exception('Access denied...');
        }
        $options = Core::object($options, Core::OBJECT_ARRAY);
        if(!array_key_exists('dir', $options)){
            throw new Exception('Directory not found...');
        }
        $dir = $object->config('ramdisk.url') .
            $posix_id .
            $object->config('ds') .
            'Inotify' .
            $object->config('ds')
        ;
        $url = $dir .
            Core::uuid() .
            $object->config('extension.sh');
        $write = [];
        $write[] = '#!/bin/bash';
        $write[] = 'inotifywait -m ' . $options['dir']  .' -e create -e moved_to --include \'.*\.json$\' |';
        $write[] = 'while read -r directory action file; do';
        $write[] = '    echo "json file" # Do your thing here!';
        $write[] = '    echo action=$action file=$file';
        $write[] = 'done';
        Dir::create($dir, Dir::CHMOD);
        File::write($url, implode(PHP_EOL, $write));
        $command = 'chmod +x ' . $url;
        exec($command);
        Dir::change($dir);
//        exec('./' . basename($url) . ' > /dev/null 2>&1 &');
        exec('./' . basename($url), $output);
        d($output);
        d($url);
        ddd($options);
    }



}