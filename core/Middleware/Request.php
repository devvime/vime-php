<?php

namespace Core\Middleware;

class Request 
{
    public function method($req)
    {
        $method = $_SERVER['REQUEST_METHOD'];
		if($method == $req) {
			return true;
		}else {
			return false;
		}
    }
}