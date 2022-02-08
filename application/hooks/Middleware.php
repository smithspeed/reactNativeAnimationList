<?php

class Middleware{

    private $ci;
    private $controller;
    private $method;
    private $isLoggedIn;

    public function __construct(){

        $this->ci =& get_instance();
        $this->controller = $this->ci->router->fetch_class();
        $this->method = $this->ci->router->fetch_method();
        $this->isLoggedIn = ($this->ci->session->userdata('username')=='') ? true : true;
    }

    public function checkPermission(){

        //echo $this->controller;
        //die();

        if($this->controller=='login' && $this->isLoggedIn){
            redirect('home');
            die();
        }
        
        $getControllerAndMehtod = $this->ci->data->get([
            'db' => 'write',
            'table' => 'page_permission',
            'where' => [
                'controller_name' => $this->controller,
                'method_name' => $this->method,
            ]
        ]);

        if(!$getControllerAndMehtod){ //IF Controller and Mehtod not found
            
            $getController = $this->ci->data->get([
                'db' => 'write',
                'table' => 'page_permission',
                'where' => [
                    'controller_name' => $this->controller,
                ]
            ]);

            if(!$getController){ //If Controller Not found

                echo "Not Authorised Path";
                die();
            }

            if($getController->is_login){

                if($this->isLoggedIn){
                    return true;
                }
                else{
                    redirect('login');
                    die();
                }
            }
            else{
                return true;
            }
        }
        else{

            if($getControllerAndMehtod->is_login){

                if($this->isLoggedIn){
                    return true;
                }
                else{
                    redirect('login');
                    die();
                }
            }
            else{
                return true;
            }
        }
    }
}



?>