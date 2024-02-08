<?php
namespace Package\R3m\Io\Raxon\Trait;

use R3m\Io\Config;

use Exception;

trait Main {

    /**
     * @throws Exception
     */
    public function parse($options=[]): void
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
}