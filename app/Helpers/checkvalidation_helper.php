<?php

use Config\Services;

if (!function_exists('isCheckvalidation')) {
    function isCheckValidation($rules, $data)
    {
        $validation = Services::validation();
        $validation->setRules($rules);

        if (!$validation->run($data)) {
            return sendResponse(
                false,
                400,
                "Validation failed",
                "user",
                [],
                $validation->getErrors()
            );
        }
       
        
        return ['status' => true]; // Return true if validation passes
    }
}
