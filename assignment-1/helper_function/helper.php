
<?php
function areAllValuesEmpty($assocArray) {
    foreach ($assocArray as $value) {
        if (!empty($value)) {
            return false; // If any value is not empty, return false
        }
    }
    return true; // All values are empty
}