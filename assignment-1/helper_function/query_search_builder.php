<?php
class QuerySearchBuilder{
    // static public function filterUndefinedStringsQuery($queryArray = [],$wrappedOp="%") {
    //     // Filter undefined values from nested arrays
    //     foreach ($queryArray as &$subArray) {
    //         if (is_array($subArray)) {
    //             $subArray = array_filter($subArray, function($value) {
    //                 return !empty(trim($value));
    //             });
    //             $subArray = array_map(function($value) use ($wrappedOp) {
    //                 return $wrappedOp.$value.$wrappedOp;
    //             },$subArray);
    //         }
    //     }
    
    //     // Filter undefined values from individual key-value pairs
    //     $filteredArray = array_filter($queryArray, function($value) {
    //         return is_array($value) ? empty($value) : !empty($value);
    //     });
    //     $filteredArray = array_map( function($value) use ($wrappedOp){
    //         return is_array($value) ? $value : $wrappedOp.$value.$wrappedOp;
    //     },$queryArray,);
    
    //     return $filteredArray;
    // }
    
    // static public function filterUndefinedStringsQuery($queryArray = [],$wrappedOp="%") {
    //     // Filter undefined values from nested arrays
    //     foreach ($queryArray as &$subArray) {
    //         if (is_array($subArray)) {
    //             // Filter empty string values from sub-array
    //             $subArray = array_filter($subArray, function($value) {
    //                 return $value !== '';
    //             });
    //             if(!empty($subArray)){
    //                 $subArray = array_map(function($value) use ($wrappedOp) {
    //                     return $wrappedOp.$value.$wrappedOp;
    //                 },$subArray);
    //             }
    //             // Remove empty sub-array
    //             if (empty($subArray)) {
    //                 unset($subArray);
    //             }
             
    //         }
    //     }
    
    //     // Filter empty strings from individual key-value pairs
    //     $filteredArray = array_filter($queryArray, function($value) {
    //         return is_array($value) ? !empty($value) : ($value !== '');
    //     });
    //      $filteredArray = array_map( function($value) use ($wrappedOp){
    //         return is_array($value) ? $value : $wrappedOp.$value.$wrappedOp;
    //     },$filteredArray,);
    //     return $filteredArray;
    // }
    /**
 * Filters undefined or empty values from a multi-dimensional associative array.
 *
 * @param array $queryArray The array to filter.
 * @param string $wrappedOp The operator to wrap values with.
 * @return array The filtered array.
 */
static public function filterUndefinedTextFields($queryArray = []) {
    // Filter empty values from nested arrays and wrap with operator
    foreach ($queryArray as &$subArray) {
        if (is_array($subArray)) {
            $subArray = array_filter($subArray, function(Conditions | string $value){
              
                if(is_string($value)) {
                    return strlen(trim($value)) > 0;
                }
                return $value->isNotBlank();
            });
            if (count($subArray) > 0) {
                $subArray = array_map(function(Conditions | string $value) {
                    if(is_string($value)) {
                        return Conditions::match($value);
                    }

                    return $value;
                }, $subArray);
            }
        }
    }
    // Filter empty strings from individual key-value pairs and wrap with operator
    $filteredArray = array_filter($queryArray, function(Conditions | string | array $value){
        switch (true) {
            case is_array($value):
                return count($value) > 0;
            case is_string($value):
                return strlen(trim($value)) > 0;
            default:
                return $value->isNotBlank();
        } 
    });
    $filteredArray = array_map(function(Conditions | string | array $value)  {
        switch (true) {
            case is_string($value):
                return Conditions::match($value);
            case is_array($value):
            default:
                return $value;
        }
   
    }, $filteredArray);

    return $filteredArray;
}

}