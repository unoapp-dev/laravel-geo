<?php
/**
 * Created by PhpStorm.
 * User: lorenzo
 * Date: 04/11/15
 * Time: 3.31
 */

namespace LorenzoGiust\GeoLaravel;


use League\Flysystem\Exception;

class GoogleDirectionResponse {

    public $raw_response;
    public $routes = [];

    public $legs = [];

    public $route;
    public $distance = 0;
    public $duration = 0;

    public function __construct($json){

        $this->raw_response = $json;

        foreach($json->routes as $route){
            $this->routes[] = new GoogleDirectionRoute($route);
        }

    }


    public function chooseRoute($typology){

        if(! in_array(['fast', 'short'], $typology))
            throw new \Exception('Unknown typology ' . $typology);

        $selected_route = null;
        $filter = $typology == "fast" ? "duration" : "distance";

        $min = PHP_INT_MAX;
        foreach($this->routes as $route){
            if ($route[$filter] < $min ) {
                $min = $route[$filter];
                $selected_route = $route;
            }
        }

        return $selected_route;

    }
}