<?php

namespace BasicBlog\Security;

/**
 * Class ValidationTrait
 *
 * Validate and Filter data
 *
 * @package BasicBlog\Security
 */
trait ValidationTrait
{
    /**
     * @param $data array
     * @param $formFieldFilters array
     *
     * @return array
     */
    public function checkDataIntegrity($data, $formFieldFilters)
    {
        $filteredData = [];

        // Apply filters
        foreach ($formFieldFilters as $key => $filter) {
            $filteredData[$key] = filter_var($data[$key], $filter);
        }

        // Check if any returned false
        if (in_array(false, $filteredData, true)) {
            $key = array_search(false, $filteredData, true);
            throw new \InvalidArgumentException(sprintf('Invalid data submitted: %s.', $key), 1);
        }

        return $filteredData;
    }
}
