<?php

namespace Encore\Admin\Helpers\Tests\Compatibility;

use Encore\Admin\Helpers\Controllers\StringOutput;
use Encore\Admin\Helpers\Controllers\TerminalController;
use Encore\Admin\Helpers\Scaffold\MigrationCreator;
use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class FrameworkCompatibilityTest extends TestCase
{
    public function testConsoleOutputRetainsLineBreaksAndCanBeCleared(): void
    {
        class_exists(TerminalController::class);
        $output = new StringOutput();
        $output->write('First');
        $output->writeln(' line');
        $output->write('Second');
        $this->assertSame("First line\nSecond", $output->getContent());
        $output->clear();
        $this->assertSame('', $output->getContent());
    }

    public function testScaffoldCreatesMigrationAndPassesItsPathToHooks(): void
    {
        $directory = sys_get_temp_dir().'/helpers-scaffold-'.bin2hex(random_bytes(8));
        mkdir($directory);
        $path = null;
        try {
            $creator = new MigrationCreator(new Filesystem());
            $hooks = [];
            $creator->afterCreate(function ($table, $file) use (&$hooks) {
                $hooks[] = [$table, $file];
            });
            $path = $creator->buildBluePrint([
                ['name' => 'name', 'type' => 'string', 'key' => '', 'nullable' => 'on'],
            ])->create('create_compatibility_examples_table', $directory, 'compatibility_examples');
            $source = file_get_contents($path);
            $this->assertStringContainsString('class CreateCompatibilityExamplesTable extends Migration', $source);
            $this->assertStringContainsString("Schema::create('compatibility_examples'", $source);
            $this->assertStringContainsString("->string('name')->nullable()", $source);
            $this->assertStringNotContainsString('Dummy', $source);
            $this->assertSame([['compatibility_examples', $path]], $hooks);
        } finally {
            if ($path !== null && file_exists($path)) {
                unlink($path);
            }
            rmdir($directory);
        }
    }
}
