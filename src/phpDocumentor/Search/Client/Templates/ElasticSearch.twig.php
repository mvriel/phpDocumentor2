<?php
$search_results = json_decode(
    file_get_contents(
        '{{configuration.uri}}/{{configuration.index}}/{{configuration.type}}/_search?q='.urlencode($_GET['q'])
    )
);

$results = array();
foreach ($search_results as $key => $result) {
    $results[$result->_id] = $result->_source->url;
    unset($search_results[$key]);
}

echo json_encode($results);
