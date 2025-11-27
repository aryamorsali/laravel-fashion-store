<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\EmailFileRequest;
use App\Http\Services\File\FileService;
use App\Models\Notification\Email;
use App\Models\Notification\EmailFile;
use Illuminate\Http\Request;

class EmailFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Email $email)
    {
        return view('admin.notification.email.file.index', compact('email'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Email $email)
    {
        return view('admin.notification.email.file.create', compact('email'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailFileRequest $request, Email $email, FileService $fileService)
    {
        if (!$request->hasFile('file')) {
            return back()->with('alert-section-error', 'No file selected.');
        }

        $uploadedFile = $request->file('file');

        // metadata
        $fileService->setFileSize($uploadedFile);
        $fileSize = $fileService->getFileSize();

        // Directory
        $fileService->setExclusiveDirectory('files' . DIRECTORY_SEPARATOR . 'email-files');

        // Upload file
        $filePath = $fileService->moveToPublic($uploadedFile);
        if ($filePath === false) {
            return back()->with('alert-section-error', 'File upload failed.');
        }
        // format
        $fileType = $fileService->getFileFormat();

        // Save record
        EmailFile::create([
            'notification_email_id' => $email->id,
            'file_path'             => $filePath,
            'file_size'             => $fileSize,
            'mime_type'             => $fileType,
        ]);

        return redirect()
            ->route('admin.notification.email.file.index', $email->id)
            ->with('alert-section-success', 'File uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Email $email, EmailFile $file)
    {
        return view('admin.notification.email.file.edit', compact('file', 'email'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailFileRequest $request, Email $email, EmailFile $file, FileService $fileService)
    {
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');

            // حذف فایل قدیمی (SoftDelete یا واقعی)
            if (!empty($file->file_path)) {
                $fileService->deleteFile($file->file_path);
            }

            // متادیتا
            $fileService->setFileSize($uploadedFile);
            $fileSize = $fileService->getFileSize();

            // تنظیم دایرکتوری
            $fileService->setExclusiveDirectory('files' . DIRECTORY_SEPARATOR . 'email-files');

            // آپلود فایل
            $filePath = $fileService->moveToPublic($uploadedFile);
            if ($filePath === false) {
                return back()->with('alert-section-error', 'File upload failed.');
            }
            // format
            $fileType = $fileService->getFileFormat();

            // آپدیت رکورد
            $file->update([
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'mime_type' => $fileType,
            ]);

            return redirect()
                ->route('admin.notification.email.file.index', $email->id)
                ->with('alert-section-success', 'File updated successfully.');
        }

        return back()->with('alert-section-error', 'No file selected.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Email $email, EmailFile $file, FileService $fileService)
    {
        if ($file->file_path) {
            $fileService->deleteFile($file->file_path);
        }
        $file->delete();
        return redirect(route('admin.notification.email.file.index', $email))->with(
            'alert-section-success',
            'Email file successfully deleted.'
        );
    }
}
