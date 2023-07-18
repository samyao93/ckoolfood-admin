<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;

class ContactMessages extends Controller
{

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $contacts = ContactMessage::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('mobile_number', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $contacts = new ContactMessage();
        }
        $contacts = $contacts->latest()->paginate(config('default_pagination'));
        return view('admin-views.contacts.list', compact('contacts','search'));

    }

    public function view($id)
    {
        $contact = ContactMessage::findOrFail($id);
        return view('admin-views.contacts.view', compact('contact'));
    }


    public function destroy(Request $request)
    {
        $contact = ContactMessage::find($request->id);
        $contact->delete();
        Toastr::success('Message Delete successfully!');
        return redirect()->route('admin.contact.list');
    }

    public function send_mail(Request $request, $id)
    {
        $contact = ContactMessage::findOrFail($id);
        $data = array('body' => $request['mail_body'],
                        'name' => $contact->name
                        );
        $business_name=Helpers::get_settings('business_name') ?? 'Stackfood';

        try {
            Mail::send('email-templates.customer-message', $data, function ($message) use ($contact,$business_name, $request) {
                $message->to($contact['email'], $business_name)
                    ->subject($request['subject']);
            });

            $contact->update([
                'reply' => json_encode([
                    'subject' => $request['subject'],
                    'body' => $request['mail_body']
                ]),
                'seen'=>1,
            ]);
            Toastr::success(translate('messages.Mail_sent_successfully'));
            return back();
        } catch (\Throwable $th) {
            info($th->getMessage());
            Toastr::error(translate('messages.Something_went_wrong_please_check_your_mail_config'));
            return back();
        }

    }
}
