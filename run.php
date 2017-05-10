<?php

echo wordLadder("hit", "cog", ["hot", "dot", "dog", "lot", "log", "cog"]);
echo wordLadder("a", "c", ["a", "b", "c"]);
echo wordLadder("hot", "dog", ["hot", "cog", "dog", "tot", "hog", "hop", "pot", "dot"]);

function wordLadder($beginWord, $endWord, $wordList)
{
    if (!checkIfEndWordExists($endWord, $wordList)) {
        return 0;
    }
    $graph = prepareGraph($beginWord, $wordList, $endWord);
    $visited = [];
    init($visited, $graph);
    return bfs($graph, $beginWord, $endWord, $visited);
}

function bfs($graph, $start, $end, $visited)
{
    $queue = [];
    $paths = [];
    array_push($queue, $start);
    $visited[$start] = 1;
    $paths[$start][] = $start;
    while (count($queue)) {
        $current = array_shift($queue);
        foreach ($graph[$current] as $key => $vertex) {
            if (!$visited[$key] && $vertex == 1 && $current != $key) {
                $paths[$key] = $paths[$current];
                $paths[$key][] = $key;
                $visited[$key] = 1;
                array_push($queue, $key);
            }
        }
    }
//    echo "\n" . implode(" -> ", $paths[$end]) . "\t";
    return count($paths[$end]);
}

function init(&$visited, $graph)
{
    foreach ($graph as $key => $vertex) {
        $visited[$key] = 0;
    }
}

function prepareGraph($root, $words, $last)
{
    $words = array_merge([$root, $last], $words);
    array_unique($words);
    $graph = [];
    foreach ($words as $word) {
        foreach ($words as $v) {
            $graph[$word][$v] = countChanges($word, $v) ? 1 : 0;
        }
    }

    return $graph;
}

function countChanges($current, $next)
{
    return count(array_diff_assoc(str_split($current), str_split($next))) === 1;
}

function checkIfEndWordExists($endWord, $list)
{
    return in_array($endWord, $list);
}