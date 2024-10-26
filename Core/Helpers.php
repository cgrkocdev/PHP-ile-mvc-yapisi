<?php
    /**
     * @param $name
     * @param array $params
     * @return string
     */

//laravelde aşağıdaki fonksiyon gibi yapıyor
    function route($name,$params = [])
    {
        return  \Jotform\Core\Route::url($name,$params);
    }