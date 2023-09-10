<?php

/**
 * @author MetaShops
 * Use this script to replace your migration files
 */

// Relative directory of migrations
define('M_DIR', getcwd() . '/database/migrations');
$migration_dir_files = scandir(M_DIR);

try {
    for ($i = 2; $i < count($migration_dir_files); $i++) {
        // Absolute path of the single file
        $currentFile = M_DIR . '/' . $migration_dir_files[$i];

        // Read contents from file (as string)
        $contents =  file_get_contents($currentFile);

        // Replace
        $searchText = 'timestamps(10)';
        $replaceByText = 'timestamps()';

        // Replaced Contents
        $replaced = str_replace($searchText, $replaceByText, $contents);

        // Write to file 
        file_put_contents($currentFile, $replaced);
    }
} catch (Exception $e) {
    echo 'Failed! ' . PHP_EOL;
    echo $e->getMessage();
} finally {
    echo 'Successfully done!' . PHP_EOL;
}
