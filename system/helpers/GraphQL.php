<?php


// system/helpers/GraphQLParser.php
namespace System\Helpers;

class GraphQL
{
    /**
     * Parse the query and return the filtered data.
     *
     * @param object $dataset The dataset you want to query.
     * @param string $query The GraphQL query.
     * @return array The filtered dataset based on the GraphQL query.
     */
    public function parse($dataset, string $query)
    {
        preg_match('/{ *(\w+)\s*{([^}]+)}/', $query, $matches);
        $entity = $matches[1] ?? null;
        $fields = array_map('trim', explode(" ", trim($matches[2] ?? '')));

        if ($entity === null) {
            return ['error' => 'Entity not found in the query'];
        }

        $result = [];

        foreach ($dataset as $item) {
            $filteredItem = [];

            foreach ($fields as $field) {
                if (isset($item[$field])) {
                    $filteredItem[$field] = $item[$field];
                }
            }

            $result[] = $filteredItem;
        }

        return $result;
    }
}