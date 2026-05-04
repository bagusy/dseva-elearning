<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $indexes = [
        'users' => ['company_id'],
        'companies' => ['user_id'],
        'departments' => ['company_id'],
        'employees' => ['company_id', 'department_id', 'user_id'],
        'courses' => ['user_id'],
        'sections' => ['course_id'],
        'videos' => ['user_id'],
        'quizzes' => ['user_id'],
        'quiz_items' => ['quiz_id'],
        'sub_sections' => ['section_id', 'video_id', 'quiz_id'],
        'course_assignments' => ['course_id', 'department_id'],
        'course_enrollments' => ['course_assignment_id', 'course_id', 'user_id', 'sub_section_id'],
        'course_enrollment_progress' => ['course_enrollment_id', 'video_id', 'quiz_id'],
        'quiz_completions' => ['course_enrollment_id', 'quiz_id'],
        'quiz_completion_answers' => ['quiz_completion_id'],
        'payment_histories' => ['user_id'],
    ];

    public function up()
    {
        foreach ($this->indexes as $tableName => $columns) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                foreach ($columns as $column) {
                    if (!Schema::hasColumn($tableName, $column)) {
                        continue;
                    }
                    $indexName = "{$tableName}_{$column}_index";
                    if (!$this->indexExists($tableName, $indexName)) {
                        $table->index($column, $indexName);
                    }
                }
            });
        }
    }

    public function down()
    {
        foreach ($this->indexes as $tableName => $columns) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                foreach ($columns as $column) {
                    $indexName = "{$tableName}_{$column}_index";
                    if ($this->indexExists($tableName, $indexName)) {
                        $table->dropIndex($indexName);
                    }
                }
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'mysql') {
            $database = $connection->getDatabaseName();
            $row = $connection->selectOne(
                'SELECT COUNT(*) AS c FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?',
                [$database, $table, $index]
            );
            return ($row->c ?? 0) > 0;
        }

        if ($driver === 'pgsql') {
            $row = $connection->selectOne(
                'SELECT COUNT(*) AS c FROM pg_indexes WHERE tablename = ? AND indexname = ?',
                [$table, $index]
            );
            return ($row->c ?? 0) > 0;
        }

        if ($driver === 'sqlite') {
            $row = $connection->selectOne(
                "SELECT COUNT(*) AS c FROM sqlite_master WHERE type = 'index' AND tbl_name = ? AND name = ?",
                [$table, $index]
            );
            return ($row->c ?? 0) > 0;
        }

        return false;
    }
};
