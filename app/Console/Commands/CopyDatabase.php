<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CopyDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:copy-from {source_database} {target_database?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy all tables and data from source database to target database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sourceDb = $this->argument('source_database');
        $targetDb = $this->argument('target_database') ?? config('database.connections.mysql.database');

        $this->info("Copying database from '{$sourceDb}' to '{$targetDb}'...");

        try {
            // Get all tables from source database
            $tables = DB::select("SHOW TABLES FROM `{$sourceDb}`");
            $tableKey = "Tables_in_{$sourceDb}";

            if (empty($tables)) {
                $this->error("No tables found in source database '{$sourceDb}'");
                return 1;
            }

            $this->info("Found " . count($tables) . " tables to copy.");

            // Create target database if it doesn't exist
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$targetDb}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("Database '{$targetDb}' created/verified.");

            // Switch to target database
            DB::statement("USE `{$targetDb}`");

            $bar = $this->output->createProgressBar(count($tables));
            $bar->start();

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                
                try {
                    // Drop table if exists in target
                    DB::statement("DROP TABLE IF EXISTS `{$targetDb}`.`{$tableName}`");
                    
                    // Copy table structure
                    DB::statement("CREATE TABLE `{$targetDb}`.`{$tableName}` LIKE `{$sourceDb}`.`{$tableName}`");
                    
                    // Copy table data
                    $rowCount = DB::statement("INSERT INTO `{$targetDb}`.`{$tableName}` SELECT * FROM `{$sourceDb}`.`{$tableName}`");
                    
                    $bar->advance();
                } catch (\Exception $e) {
                    $this->newLine();
                    $this->warn("Error copying table '{$tableName}': " . $e->getMessage());
                }
            }

            $bar->finish();
            $this->newLine(2);
            $this->info("Database copy completed successfully!");
            $this->info("All tables from '{$sourceDb}' have been copied to '{$targetDb}'.");

            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}

