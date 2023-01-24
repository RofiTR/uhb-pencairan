<?php

namespace App\Http\Livewire\System;

use Livewire\Component;
use WireUi\Traits\Actions;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Tools extends Component
{
    use Actions;

    public $sqlQuery;
    public $users, $method, $keys = [], $values = [];
    public $updateMode = false;
    public $inputs = [];
    public $i = 1;

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs, $i);
    }

    public function remove($i)
    {
        unset($this->inputs[$i]);
    }

    public function render()
    {
        return view('livewire.system.tools');
    }

    private function resetInputFields()
    {
        $this->method = '';
        $this->keys   = [];
        $this->values = [];
        $this->inputs = [];
    }

    public function store()
    {
        $this->validate(
            [
                'method'   => 'required',
                'keys.*'   => 'required',
                'values.*' => 'required',
            ]
        );

        foreach ($this->keys as $key => $value) {
            if ($value == 'NA')
                $this->keys[$key] = 'name';
        }

        try {
            $params = array_combine($this->keys, $this->values);
            if ($params)
                Artisan::call($this->method, $params);
            else
                Artisan::call($this->method);
            if ($this->method == 'down')
                $redirect = $params['--secret'];
            else if ($this->method == 'up')
                $redirect = '/';

            $flash = Artisan::output();
            if (isset($redirect))
                $flash .= ' ' . ' <a href="' . url($redirect) . '">Refresh</a>';

            $this->notification([
                'title'       => null,
                'description' => $flash,
                'icon'        => 'success',
            ]);

            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->notification([
                'title'       => null,
                'description' => $e->getMessage(),
                'icon'        => 'error',
            ]);
        }
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            $this->notification([
                'title'       => null,
                'description' => Artisan::output(),
                'icon'        => 'success',
            ]);

            Artisan::call('route:clear');
            $this->notification([
                'title'       => null,
                'description' => Artisan::output(),
                'icon'        => 'success',
            ]);

            Artisan::call('view:clear');
            $this->notification([
                'title'       => null,
                'description' => Artisan::output(),
                'icon'        => 'success',
            ]);

            Artisan::call('config:clear');
            $this->notification([
                'title'       => null,
                'description' => Artisan::output(),
                'icon'        => 'success',
            ]);
        } catch (\Exception $e) {
            $this->notification([
                'title'       => null,
                'description' => $e->getMessage(),
                'icon'        => 'error',
            ]);
        }
    }

    public function linkStorage()
    {
        try {
            Artisan::call('storage:link');
            $this->notification([
                'title'       => null,
                'description' => 'Symlink berhasil',
                'icon'        => 'success',
            ]);
        } catch (\Exception $e) {
            $this->notification([
                'title'       => null,
                'description' => $e->getMessage(),
                'icon'        => 'error',
            ]);
        }
    }

    public function runSql()
    {
        try {
            DB::unprepared($this->sqlQuery);
            $this->sqlQuery = '';
            $this->notification([
                'title'       => null,
                'description' => 'Query berhasil',
                'icon'        => 'success',
            ]);
        } catch (\Exception $e) {
            $this->notification([
                'title'       => null,
                'description' => $e->getMessage(),
                'icon'        => 'error',
            ]);
        }
    }

    public function dumpSql()
    {
        // try {
        $file = '/storage/dump/dump-' . date('ymdHis') . '.sql';
        $process = Process::fromShellCommandline("pg_dump --user=env('DB_USERNAME') --password=env('DB_PASSWORD') env('DB_DATABASE') >> $file");
        // $process->setWorkingDirectory(base_path());
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $this->notification([
            'title'       => null,
            'description' => 'Dump berhasil',
            'icon'        => 'success',
        ]);
        // } catch (\Exception $e) {
        //     $this->notification([
        //         'title'       => null,
        //         'description' => $e->getMessage(),
        //         'icon'        => 'error',
        //     ]);
        // }
    }

    public function updateApp()
    {
        Artisan::call('down');
        try {
            $process = Process::fromShellCommandline('git pull origin master');
            $process->setWorkingDirectory(base_path());
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $this->notification([
                'title'       => null,
                'description' => $process->getOutput(),
                'icon'        => 'success',
            ]);
        } catch (\Exception $e) {
            $this->notification([
                'title'       => null,
                'description' => $e->getMessage(),
                'icon'        => 'error',
            ]);
        }
        Artisan::call('up');
    }
}
