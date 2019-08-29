<?php

$startPath = 'd:\path\to\music';
$dryRun = false;

$done = $error = $found = 0;
$artistFolders = getDirectoryContents($startPath);
if (is_array($artistFolders)) {
    foreach ($artistFolders as $artist) {
        //echo $startPath . DIRECTORY_SEPARATOR . $artist;
        if (is_dir($startPath . DIRECTORY_SEPARATOR . $artist)) {
            //echo 'looking into ' . $artist . PHP_EOL;
            $albumFolders = getDirectoryContents($startPath . DIRECTORY_SEPARATOR . $artist);
            foreach ($albumFolders as $album) {
                if (is_dir($startPath . DIRECTORY_SEPARATOR . $artist . DIRECTORY_SEPARATOR . $album)) {
                    $trackFiles = getDirectoryContents($startPath . DIRECTORY_SEPARATOR . $artist . DIRECTORY_SEPARATOR . $album);
                    foreach ($trackFiles as $track) {
                        if (is_file($startPath . DIRECTORY_SEPARATOR . $artist . DIRECTORY_SEPARATOR . $album . DIRECTORY_SEPARATOR . $track)) {
                            $found++;
                            $filename = $artist . ' - ' . $album . ' - ' . $track;
                            $src = $startPath . DIRECTORY_SEPARATOR . $artist . DIRECTORY_SEPARATOR . $album . DIRECTORY_SEPARATOR . $track;
                            $dest = $startPath . DIRECTORY_SEPARATOR . $filename;
                            if (!$dryRun) {
                                if (copy($src, $dest)) {
                                    $done++;
                                } else {
                                    $error++;
                                }
                            }
                            echo 'from: ' . $src . PHP_EOL;
                            echo 'to: ' . $dest . PHP_EOL . PHP_EOL;
                        }
                    }
                }
            }
        }
    }
    echo 'DONE!' . ($dryRun ? ' (dryrun)' : '') . PHP_EOL;
    echo 'files found: ' . $found . PHP_EOL;
    echo 'files copied: ' . $done . PHP_EOL;
    echo 'errors: ' . $error . PHP_EOL;
} else {
    echo 'Source folder has no directories.' . PHP_EOL;;
}

function getDirectoryContents($path) {
    if (!is_dir($path)) {
        return false;
    }

    $contents = array_diff(scandir($path), array('.', '..'));
    return $contents;
}
