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
        $dir = $object->config('project.dir.data') .
            'Inotify' .
            $object->config('ds')
        ;
        $url = $dir .
            Core::uuid() .
            $object->config('extension.sh');
        Core::interactive();
        echo $options['dir'] . PHP_EOL;
        $write = [];
        $write[] = '#!/bin/bash';
        $write[] = 'inotifywait -m ' . $options['dir']  .' -e create -e moved_to | \\';
        $write[] = 'while read -r directory action file; do \\';
        $write[] = 'if [[ "$file" =~ .*json$ ]]; then # Does the file end with .xml? \\';
        $write[] = 'echo "xml file" # If so, do your thing here! \\';
        $write[] = 'fi \\';
        $write[] = 'done';

        Dir::create($dir, Dir::CHMOD);
        Dir::create($options['dir'], Dir::CHMOD);

        File::write($url, implode(PHP_EOL, $write));
        File::permission($object, [
            'dir' => $dir,
            'file' => $url,
            'options_dir'  => $options['dir']
        ]);
        Dir::change($dir);
        $command = 'chmod +x ' . basename($url);
        exec($command);
//        exec('./' . basename($url) . ' > /dev/null 2>&1 &');
        exec('./' . basename($url), $output);
        d($output);
        d($url);
        ddd($options);
    }



}