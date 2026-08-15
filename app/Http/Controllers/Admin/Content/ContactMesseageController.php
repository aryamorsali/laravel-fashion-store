<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\ContactMessage;
use Illuminate\Http\Request;

class ContactMesseageController extends Controller
{
    public function index()
    {
        $contactMessages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.content.contact.index', compact('contactMessages'));
    }



    public function show(ContactMessage $contact)
    {
        return view('admin.content.contact.show', compact('contact'));
    }
}
