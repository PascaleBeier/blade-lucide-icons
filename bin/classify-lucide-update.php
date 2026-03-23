#!/usr/bin/env php
<?php

declare(strict_types=1);

function option(array $options, string $name): string
{
    $value = $options[$name] ?? null;

    if (! is_string($value) || $value === '') {
        fwrite(STDERR, sprintf("Missing required option --%s\n", $name));
        exit(1);
    }

    return $value;
}

function svgHashes(string $directory): array
{
    if (! is_dir($directory)) {
        fwrite(STDERR, sprintf("Directory does not exist: %s\n", $directory));
        exit(1);
    }

    $icons = [];
    $entries = scandir($directory);

    if ($entries === false) {
        fwrite(STDERR, sprintf("Unable to read directory: %s\n", $directory));
        exit(1);
    }

    foreach ($entries as $entry) {
        if (! str_ends_with($entry, '.svg')) {
            continue;
        }

        $path = $directory.DIRECTORY_SEPARATOR.$entry;

        if (! is_file($path)) {
            continue;
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            fwrite(STDERR, sprintf("Unable to read file: %s\n", $path));
            exit(1);
        }

        $icons[$entry] = sha1($contents);
    }

    ksort($icons);

    return $icons;
}

function writeGithubOutput(string $path, string $name, string $value): void
{
    file_put_contents(
        $path,
        sprintf("%s<<OUTPUT\n%s\nOUTPUT\n", $name, $value),
        FILE_APPEND
    );
}

function formatIconList(array $icons, int $limit = 25): string
{
    if ($icons === []) {
        return 'none';
    }

    $visibleIcons = array_slice($icons, 0, $limit);
    $suffix = count($icons) > $limit
        ? sprintf(', and %d more', count($icons) - $limit)
        : '';

    return implode(', ', $visibleIcons).$suffix;
}

function markdownSection(string $title, array $icons): string
{
    if ($icons === []) {
        return sprintf("- %s: none", $title);
    }

    $lines = [sprintf("- %s (%d):", $title, count($icons))];

    foreach ($icons as $icon) {
        $lines[] = sprintf("  - `%s`", $icon);
    }

    return implode("\n", $lines);
}

$options = getopt('', [
    'before:',
    'after:',
    'current-version:',
    'latest-version:',
    'message-file:',
    'github-output::',
]);

$before = svgHashes(option($options, 'before'));
$after = svgHashes(option($options, 'after'));
$currentVersion = option($options, 'current-version');
$latestVersion = option($options, 'latest-version');
$messageFile = option($options, 'message-file');
$githubOutput = $options['github-output'] ?? getenv('GITHUB_OUTPUT') ?: null;

$removedIcons = array_keys(array_diff_key($before, $after));
$addedIcons = array_keys(array_diff_key($after, $before));
$changedIcons = [];

foreach (array_intersect_key($after, $before) as $icon => $hash) {
    if ($before[$icon] !== $hash) {
        $changedIcons[] = $icon;
    }
}

sort($removedIcons);
sort($addedIcons);
sort($changedIcons);

$hasChanges = $removedIcons !== [] || $addedIcons !== [] || $changedIcons !== [];
$releaseType = $removedIcons !== []
    ? 'major'
    : ($hasChanges ? 'minor' : 'none');

$subject = match ($releaseType) {
    'major' => sprintf('feat!: sync Lucide to %s', $latestVersion),
    'minor' => sprintf('feat(icons): sync Lucide to %s', $latestVersion),
    default => sprintf('chore(icons): sync Lucide to %s', $latestVersion),
};

$bodyLines = [
    sprintf('Updated Lucide from %s to %s.', $currentVersion, $latestVersion),
    '',
    sprintf('Source: https://github.com/lucide-icons/lucide/releases/tag/%s', $latestVersion),
    '',
    sprintf('Added icons: %d', count($addedIcons)),
    sprintf('Changed icons: %d', count($changedIcons)),
    sprintf('Removed icons: %d', count($removedIcons)),
];

if ($addedIcons !== []) {
    $bodyLines[] = sprintf('Added: %s', formatIconList($addedIcons));
}

if ($changedIcons !== []) {
    $bodyLines[] = sprintf('Changed: %s', formatIconList($changedIcons));
}

if ($removedIcons !== []) {
    $bodyLines[] = sprintf('Removed: %s', formatIconList($removedIcons));
    $bodyLines[] = '';
    $bodyLines[] = sprintf(
        'BREAKING CHANGE: Lucide removed or renamed these icons: %s',
        formatIconList($removedIcons)
    );
}

$commitMessage = $subject."\n\n".implode("\n", $bodyLines)."\n";

$messageDirectory = dirname($messageFile);

if (! is_dir($messageDirectory) && ! mkdir($messageDirectory, 0777, true) && ! is_dir($messageDirectory)) {
    fwrite(STDERR, sprintf("Unable to create directory: %s\n", $messageDirectory));
    exit(1);
}

file_put_contents($messageFile, $commitMessage);

$summary = implode("\n", [
    sprintf('## Lucide update classification: `%s`', $releaseType),
    '',
    sprintf('- Current version: `%s`', $currentVersion),
    sprintf('- Latest version: `%s`', $latestVersion),
    sprintf('- Has generated icon changes: `%s`', $hasChanges ? 'yes' : 'no'),
    markdownSection('Added icons', $addedIcons),
    markdownSection('Changed icons', $changedIcons),
    markdownSection('Removed icons', $removedIcons),
]);

fwrite(STDOUT, $summary."\n");

if (is_string($githubOutput) && $githubOutput !== '') {
    writeGithubOutput($githubOutput, 'has_changes', $hasChanges ? 'true' : 'false');
    writeGithubOutput($githubOutput, 'release_type', $releaseType);
    writeGithubOutput($githubOutput, 'commit_message', $commitMessage);
    writeGithubOutput($githubOutput, 'commit_subject', $subject);
    writeGithubOutput($githubOutput, 'message_file', $messageFile);
}
