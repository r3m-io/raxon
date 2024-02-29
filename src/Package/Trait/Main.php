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
//        $dir = $object->config('project.dir.data') .
//            'Inotify' .
//            $object->config('ds')
//        ;
//        $url = $dir .
//            Core::uuid() .
//            $object->config('extension.sh');
        Core::interactive();
        echo $options['dir'] . PHP_EOL;
        Dir::create($options['dir'], Dir::CHMOD);
        File::permission($object, [
//            'dir' => $dir,
//            'file' => $url,
            'options_dir'  => $options['dir']
        ]);

        $fd = inotify_init();
        $read = array($fd);
        $write = null;
        $except = null;
        stream_select($read,$write,$except,0);
        stream_set_blocking($fd, 0);
        $watch_descriptor = inotify_add_watch($fd, $options['dir'], IN_CREATE | IN_MOVED_TO);
        while(true){
            $events = inotify_read($fd);
            d($events);
            usleep(2000); // 2ms
            $time = microtime(true);
            if($time > $object->config('time.start') + 60){
                break;
            }
        }
        inotify_rm_watch($fd, $watch_descriptor);
        fclose($fd);
    }
}