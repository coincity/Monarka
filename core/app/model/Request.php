<?php 
class Request {
    public function __construct() {
        $this->parameters = [];
        $this->body = null;
        $this->method = null;
        $this->headers = null;
    }

    public static function capture() {
        $request = new Request();
        $request->method = $_SERVER['REQUEST_METHOD'];
        echo $_SERVER['REQUEST_METHOD'];
        switch($request->method) {
            case "GET" :
                $request->parameters = $_GET;
                break;
            case "POST" :
                $request->parameters = $_POST;
                $request->body = json_decode(file_get_contents('php://input'), true);
                break;            
        }

        return $request;
    }

    public function respond($statusCode, $body=null) {
        http_response_code($statusCode);
        ob_get_clean();
        if($body != null) {
            echo json_encode($body);
        }
    }

    public function nocontent() {
        $this->respond(201);
    }

    public function ok($body) {
        $this->respond(200, $body);
    }

    public function notFound() {
        $this->respond(404);
    }

    public function unauthorized() {
        $this->respond(401);
    }

    public function badRequest() {
        $this->respond(400);
    }
}

?>