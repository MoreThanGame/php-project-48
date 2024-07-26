<?php

namespace Differ\Cli;

function parseArguments()
{
    $doc = <<<DOC
  Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]

DOC;
    $args = \Docopt::handle($doc, array('version' => '1.0'));
    return $args;
  // print_r($args);
}
