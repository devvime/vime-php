<?php

namespace Core;

use Rain\Tpl;

class Page {

    private $tpl;
    private $options;
    private $defaults = [
        "header"=>true,
        "footer"=>true,
        "data"=>[]
    ];

    public function __construct($opts = [], $dir = false, $header = [])
    {
        $tpl_dir = "/../app/views/";

        $this->options = array_merge($this->defaults, $opts);
        // config
        $config = array(
            "tpl_dir"       => __DIR__ . $tpl_dir . $dir,
            "cache_dir"     => __DIR__ . "/../app/views-cache/",
            "debug"         => false
        );

        Tpl::configure( $config );

        $this->tpl = new Tpl;

        $this->setData($this->options["data"]);

        if ($this->options["header"] === true) $this->render("header",$header);
    }

    private function setData($data = array())
    {
        foreach ($data as $key => $value) {
            $this->tpl->assign($key,$value);
        }
    }

    public function render($name, $data = array(), $retunrHTML = false)
    {
        $this->setData($data);

        return $this->tpl->draw($name, $retunrHTML);
    }

    public function __destruct()
    {
       if ($this->options["footer"] === true) $this->tpl->draw("footer");
    }

}