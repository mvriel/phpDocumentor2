<?php
$query = isset($_GET['q']) ? 'q=' . urlencode($_GET['q']) : '';
$search_results = json_decode(
    file_get_contents(
        '{{configuration.uri}}/{{configuration.index}}/{{configuration.type}}/_search?'.$query
    ),
    true
);

$results = array();
foreach ($search_results as $key => $result) {
    $results[$result->_id] = $result->_source;
    unset($search_results[$key]);
}

echo json_encode($results);
