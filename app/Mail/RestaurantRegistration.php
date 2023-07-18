<?php

namespace App\Mail;

use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestaurantRegistration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $status;
    protected $name;

    public function __construct($status, $name)
    {
        $this->status = $status;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('email-templates.self-registration')->with(['status'=>$this->status, 'name'=>$this->name]);

        $company_name = BusinessSetting::where('key', 'business_name')->first()->value;
        $data=EmailTemplate::where('type','admin')->where('email_type', 'restaurant_registration')->first();
        $template=$data?$data->email_template:1;
        $url = '';
        $restaurant_name = $this->name;
        // dd($template);
        $title = Helpers::text_variable_data_format( value:$data['title']??'',restaurant_name:$restaurant_name??'');
        $body = Helpers::text_variable_data_format( value:$data['body']??'',restaurant_name:$restaurant_name??'');
        $footer_text = Helpers::text_variable_data_format( value:$data['footer_text']??'',restaurant_name:$restaurant_name??'');
        $copyright_text = Helpers::text_variable_data_format( value:$data['copyright_text']??'',restaurant_name:$restaurant_name??'');
        return $this->view('email-templates.new-email-format-'.$template, ['company_name'=>$company_name,'data'=>$data,'title'=>$title,'body'=>$body,'footer_text'=>$footer_text,'copyright_text'=>$copyright_text,'url'=>$url]);
    }
}
