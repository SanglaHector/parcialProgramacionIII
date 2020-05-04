<?php
require_once dirname(__DIR__,1).'/PHP/error.php';
class Retorno
{
    public $Ok;
    public $error;
    public $jwt;
    public $json;

    function __construct($Ok,$error,$jwt,$json)
    {
        $this->Ok = $Ok;
        if(is_a($error, 'MyError'))
        {
            $this->error = new MyError($error->descripcion,$error->exceptionMessege);
        }else
        {
            $this->error = null;
        }
        $this->jwt = $jwt;
        $this->json  = $json;
    }
    public static function armarRetorno($OK,$descError,$descException, $jwt, $json)//Solo usar esta.
    {
        $error = new MyError($descError,$descException);
        return json_encode(new Retorno($OK,$error,$jwt,$json));
    }
}