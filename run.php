<?php
/**
 * Created by PhpStorm.
 * User: k.K
 * Date: 08.05.2017
 * Time: 22:42
 */

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
    $counter = 0;
    while (count($queue)) {
        $current = array_shift($queue);

        $counter++;
        foreach ($graph[$current] as $key => $vertex) {
            if (!$visited[$key] && $vertex == 1 && $current != $key) {
                $paths[$key] = $paths[$current];
                $paths[$key][] = $key;
                $visited[$key] = 1;
                array_push($queue, $key);
            }
        }
    }
//    echo "\n" . implode(" -> ", $paths[$end]);
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
    $size = strlen($root);
    $words = array_merge([$root, $last], $words);
    array_unique($words);
    $graph = [];
    foreach ($words as $word) {
        foreach ($words as $v) {
            $graph[$word][$v] = countChanges($word, $v, $size) === 1 ? 1 : 0;
        }
    }

    return $graph;
}

function countChanges($current, $next, $size)
{
    $fromStart = strspn($next ^ $current, "\0");
    $fromEnd = strspn(strrev($next) ^ strrev($current), "\0");
    $changes = ($size - $fromEnd) - $fromStart;

    return $changes;
}

function checkIfEndWordExists($endWord, $list)
{
    return in_array($endWord, $list);
}