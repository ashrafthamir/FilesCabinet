<?php

namespace App\Http\Controllers;

use App\FileStore;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;

class FileStoreController extends Controller
{
    public function getFiles()
    {
        // retreiving all files
        $files = Auth::user()->filesCabinet()->orderBy('created_at', 'desc')->get();
        return view('home', compact('files'));
    }

    public function addFile(Request $request)
    {
        // validating input
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:1024',
        ]);

        if ($validator->fails()) {
            return back()->WithErrors($validator->errors()->all())->withInput();
        }

        $msg = ['alert' => 'There was an error'];

        // preparing file to be stored
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientoriginalName();
            $fileExtension = $file->getClientOriginalExtension();
            $fileType = $file->getMimeType();
            $fileUniqueName = Carbon::now()->format('y_m_d_H_i_s') . '_' . str_random(16) . '.' . $fileExtension;
        } else {
            return redirect()->route('home')->with($msg);
        }

        // creating new file
        $fileStore = new FileStore();
        $fileStore->file_name = $fileName;
        $fileStore->file_unique_name = $fileUniqueName;
        $fileStore->content_type = $fileType;
        $fileStore->user_id = Auth::user()->id;
        $storagePath = Storage::disk('s3')->put($fileUniqueName, $file);
        $fileStore->storage_path = $storagePath;

        if ($fileStore->save()) {
            $msg = ['message' => 'File stored successfully!'];
        }

        // redirecting with info message
        return redirect()->route('home')->with($msg);
    }

    public function DownloadFile(Request $request)
    {
        // download the file
        try {
            $fileRecord = Auth::user()->filesCabinet()->find($request->file_id);
            $file = Storage::disk('s3')->get(Auth::user()->filesCabinet()->where('id', $request->file_id)->value('storage_path'));
            $headers = [
                'Content-Disposition' => "attachment; filename={$fileRecord->file_name}",
                'filename' => $fileRecord->file_name
            ];
            return response($file, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->route('home')->with(['alert' => 'No such file']);
        }
    }

    public function deleteFile(Request $request)
    {
        $msg = ['alert' => 'Error in deleting file'];

        // delete a file from storage and from the file_stores table
        if (Storage::disk('s3')->delete(Auth::user()->filesCabinet()->where('id', $request->file_id)->value('storage_path'))) {
            FileStore::find($request->file_id)->delete();
            $msg = ['message' => 'File deleted successfully!'];
        }
        return redirect()->route('home')->with($msg);
    }

    public function previewFile(Request $request)
    {
        // preview file
        $fileRecord = Auth::user()->filesCabinet()->find($request->file_id);
        $fileType = $fileRecord->content_type;
        $file = Storage::disk('s3')->get(Auth::user()->filesCabinet()->where('id', $request->file_id)->value('storage_path'));
        return response($file, 200)->withHeaders(['Content-Type' => $fileType]);
    }
}
