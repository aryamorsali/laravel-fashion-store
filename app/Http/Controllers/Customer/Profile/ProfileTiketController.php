<?php

namespace App\Http\Controllers\Customer\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Profile\Ticket\AnswerTicketRequest;
use App\Http\Requests\Customer\Profile\Ticket\StoreTicketRequest;
use App\Http\Requests\ProfileTicketRequest;
use App\Http\Services\Image\ImageService;
use App\Http\Services\Image\ImageToolsService;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketCategory;
use App\Models\Ticket\TicketFile;
use App\Models\Ticket\TicketPriority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProfileTiketController extends Controller
{
    public function index()
    {
        $tickets = Auth::user()->tickets()->where('parent_id', null)->with(['category', 'priority'])->orderBy('created_at', 'desc')->paginate(10);
        return view('customer.profile.ticket.index', compact('tickets'));
    }

    public function create()
    {
        $categories = TicketCategory::where('status', 1)->get();
        $priorities = TicketPriority::where('status', 1)->get();
        return view('customer.profile.ticket.create', compact('categories', 'priorities'));
    }


    public function store(StoreTicketRequest $request, ImageService $imageService)
    {
        $data = $request->validated();
        $file = $request->file('image');
        $imagePath = null;

        if ($file) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'ticket-files');
            $result = $imageService->save($file);

            if ($result === false) {
                return redirect()->back()->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $imagePath = $result;
        }
        try {
            DB::transaction(function () use ($data, $file, $imagePath) {
                $ticket = Ticket::create([
                    'user_id' => Auth::user()->id,
                    'subject' => $data['subject'],
                    'description' => $data['description'],
                    'category_id' => $data['category_id'],
                    'priority_id' => $data['priority_id'],
                ]);

                if ($file) {
                    TicketFile::create([
                        'user_id' => Auth::user()->id,
                        'ticket_id' => $ticket->id,
                        'file_path' => $imagePath,
                        'file_size' => filesize(public_path($imagePath)),
                        'type' => $file->getMimeType(),
                    ]);
                }
            });
        } catch (Throwable $exception) {
            // در صورت خطای دیتابیس فایل آپلود شده را حذف کن
            if ($imagePath) {
                $imageService->deleteImage(public_path($imagePath));
            }

            return back()->with('toast-error', 'There was an error submitting your ticket.');
        }


        return redirect()->route('customer.profile.ticket.index')->with('toast-success', 'Ticket created successfuly.');
    }

    public function change(Ticket $ticket)
    {
        if ($ticket->status === 1) {
            return redirect()->back()->with('toast-error', 'This ticket is closed. Please submit a new ticket to resolve the issue.');
        }

        $ticket->update([
            'status' => $ticket->status === 0 ? 1 : 0
        ]);
        return redirect()->back();
    }

    public function show(Ticket $ticket)
    {
        return view('customer.profile.ticket.show', compact('ticket'));
    }

    public function answer(AnswerTicketRequest $request, Ticket $ticket)
    {
        if ($ticket->status === 1) {
            return redirect()->back()->with('toast-error', 'This ticket is closed. Please submit a new ticket to resolve the issue.');
        }

        $data = $request->validated();

        Ticket::create([
            'user_id' => Auth::user()->id,
            'subject' => $ticket->subject,
            'description' => $data['description'],
            'category_id' => $ticket->category_id,
            'priority_id' => $ticket->priority_id,
            'parent_id' => $ticket->id,
        ]);
        return redirect()->back()->with('toast-success', 'Message sent successfully.');
    }
}
