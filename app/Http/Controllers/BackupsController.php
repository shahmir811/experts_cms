<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view backup', ['only'=> ['index']]);
        $this->middleware('permission:delete backup', ['only'=> ['delete']]);
    }
    public function index()
    {
        $disk = Storage::disk('backup');
        $directory = env('APP_NAME', 'laravel-backup');

        $files = $disk->files($directory);

        $backups = collect($files)->map(function ($file) use ($disk) {
            return [
                'name' => basename($file),
                'size' => $disk->size($file),
                'last_modified' => $disk->lastModified($file),
                'path' => $file,
            ];
        })->sortByDesc('last_modified')->values();

        return view('pages.backups.index', compact('backups'));
    }

    public function delete(Request $request)
    {
        $path = $request->input('path');

        if (Storage::disk('backup')->exists($path)) {
            Storage::disk('backup')->delete($path);
            return back()->with('status', 'Backup deleted successfully!');
        }

        return back()->with('error', 'Backup file not found!');
    }

}
