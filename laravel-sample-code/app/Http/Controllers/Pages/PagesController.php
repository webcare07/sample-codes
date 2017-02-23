<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Profile;
use App\Business;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Contactus;

class PagesController extends Controller
{

    /**
     * faq
     * action method to load faq page
     * created Sep 9, 2016
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function faq()
    {
        if(isset(Auth::user()->id))
        {
        return view('pages.faq_inner');
        }
        else
        {
          return view('pages.faq');
        }
    }

    /**
     * aboutus
     * action method to load aboutus page
     * created Sep 9, 2016
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function aboutus()
    {
         if(isset(Auth::user()->id))
        {
        return view('pages.aboutus_inner');
        }
        else
        {
          return view('pages.aboutus');  
        }       
    }

    /**
     * bank_account_instant_verification_user_terms
     * action method to load bank account instant verification page
     * created Sep 9, 2016
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function bank_account_instant_verification_user_terms()
    {
        if(isset(Auth::user()->id))
        {
            return view('pages.bankaccountinstantverificationuserterms_inner');
        }
        else
        {
            return view('pages.bankaccountinstantverificationuserterms');
        }

    }


    /**
     * submit_feedback
     * action method to load submit_feedback page
     * created Sep 9, 2016
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
	public function submit_feedback()
    {

        if(isset(Auth::user()->id)) {
            $messages = ['cardnumber.unique' => "An error occurred. The feedback can't be added.",];
            $validator = Validator::make(Input::all(), [
                  'message' => 'required'
            ], $messages);

            if ($validator->fails()) {
                return redirect('pages/feedbacks/')->withErrors($validator)->withInput();
            } else {
                $contactus = new Contactus;
                $contactus->user_id = Auth::user()->id;	
				$accoutnType  =  auth()->user()->account_type;
                if($accoutnType=='business')
                {
                    $businessData = Business::where('user_id' , '=', auth()->user()->id)->first();
                    $contactus->name  = $businessData->name;
                }
                else
                {
                    $personalData     =   Profile::find(auth()->user()->id);
                    $name             =   $personalData->fname."&nbsp;".$personalData->mname."&nbsp;".$personalData->lname;
                    $contactus->name  =   $name;
                }
                $contactus->email   = Auth::user()->email;
                $contactus->message = Input::get('message');

               Mail::send('email.emailFeedback', ["data" => $contactus, "contactus" => $contactus], function($message) use ($contactus) {
                     $message->from($contactus->email, 'Main Site Feedback');
                     $message->to('mohindsrgukr@yandex.com')->subject('Feedback');
                 }); 
                 return Redirect('pages/feedbacks/')->with('emessage', 'your feedback submited successfully.');
             }
         }
         else {
             $messages = ['cardnumber.unique' => "An error occurred. The feedback can't be added.",];
             $validator = Validator::make(Input::all(), [
				 'email' => 'required',
                 'message' => 'required'
             ], $messages);

             if ($validator->fails()) {
                 return redirect('pages/support-ticket/')->withErrors($validator)->withInput();
             } else {
                 $contactus = new Contactus;
                 $contactus->name = Input::get('name');
                 $contactus->email = Input::get('email');
                 $contactus->message = Input::get('message');

                Mail::send('email.emailFeedback', ["data" => $contactus, "contactus" => $contactus], function($message) use ($contactus) {
                     $message->from($contactus->email, 'Main Site Feedback');
                     $message->to('mohindsrgukr@yandex.com')->subject('Feedback');
                 }); 
                return Redirect('pages/feedbacks/')->with('emessage', 'your feedback submited successfully.');
            }
        }

    }

    /**
     * submit_ticket
     * action method to load submit_ticket page
     * created Sep 9, 2016
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function submit_ticket()
    {

        if(isset(Auth::user()->id)) {
            $messages = ['cardnumber.unique' => "An error occurred. The ticket can't be added.",];
            $validator = Validator::make(Input::all(), [
                'subject' => 'required',
                'message' => 'required',
                'captcha' => 'required|valid_captcha',
            ], $messages);

            if ($validator->fails()) {
                return redirect('pages/support-ticket/')->withErrors($validator)->withInput();
            } else {
                $contactus = new Contactus;
                $contactus->user_id = Auth::user()->id;
				$accoutnType  =  auth()->user()->account_type;
                if($accoutnType=='business')
                {
                    $businessData = Business::where('user_id' , '=', auth()->user()->id)->first();
                    $contactus->name  = $businessData->name;
                }
                else
                {
                    $personalData     =   Profile::find(auth()->user()->id);
                    $name             =   $personalData->fname."&nbsp;".$personalData->mname."&nbsp;".$personalData->lname;
                    $contactus->name  =   $name;
                }
                $contactus->email   = Auth::user()->email;
                $contactus->subject = Input::get('subject');
                $contactus->message = Input::get('message');
                $contactus->status = 1;
                $contactus->created = date("Y-m-d h:i:s");
                $contactus->save();
                if ( Input::has('submit_files') )
                {
                    $files = array('submit_files' => Input::file('submit_files'));
                    if(!empty($files))
                    {
                        foreach($files as $key => $file)
                        {
                            foreach($file as $fileKey => $fileData)
                            {
                                $destinationPath = base_path() . '/public/submit_ticket';
                                if (!file_exists($destinationPath)) {
                                    mkdir($destinationPath, 0777, true);
                                }
                                $extension = $fileData->getClientOriginalExtension();
                                $filename =   time() ."_".$fileKey. "." . $extension;
                                $fileData->move($destinationPath, $filename);
                                $fileURL[] = base_path()."/public/submit_ticket".$filename;
                            }
                        }
                    }
                }
                      Mail::send('email.contactus', ["data" => $contactus, "contactus" => $contactus], function($message) use ($contactus) {
                       $message->from($contactus->email, ' Main Site Support Submit Ticket');
                       $message->to('mohindsrgukr@yandex.com')->subject('Submit Ticket');
                          if ( Input::hasFile('submit_files') )
                          {
                              foreach(Input::file('submit_files') as $file) {
                                  if($file) {
                                      $message->attach($file->getRealPath(), array(
                                          'as' => $file->getClientOriginalName(),
                                          'mime' => $file->getMimeType()));
                                  }
                              }
                          }
                   }); 
                return Redirect('pages/support-ticket/')->with('emessage', 'your query submited successfully. we will contact you soon.');
            }
        }
        else {
            $messages = ['cardnumber.unique' => "An error occurred. The ticket can't be added.",];
            $validator = Validator::make(Input::all(), [
                'name' => 'required',
                'email' => 'required|email',
                'subject' => 'required',
                'message' => 'required',
                'captcha' => 'required|valid_captcha',
            ], $messages);

            if ($validator->fails()) {
                return redirect('pages/support-ticket/')->withErrors($validator)->withInput();
            } else {
                $fname = Input::get('name');
                $lname = Input::get('lname');
                $name  = "$fname $lname";
                $contactus = new Contactus;
                $contactus->name = $name;
                $contactus->email = Input::get('email');
                $contactus->subject = Input::get('subject');
                $contactus->message = Input::get('message');
                $contactus->status = 1;
                $contactus->created = date("Y-m-d h:i:s");
                $contactus->save();
                if ( Input::has('submit_files') )
                {
                    $files = array('submit_files' => Input::file('submit_files'));
                    if(!empty($files))
                    {
                        foreach($files as $key => $file)
                        {
                            foreach($file as $fileKey => $fileData)
                            {
                                $destinationPath = base_path() . '/public/submit_ticket';
                                if (!file_exists($destinationPath)) {
                                    mkdir($destinationPath, 0777, true);
                                }
                                $extension = $fileData->getClientOriginalExtension();
                                $filename =   time() ."_".$fileKey. "." . $extension;
                                $fileData->move($destinationPath, $filename);
                                $fileURL[] = "/public/submit_ticket".$filename;
                            }
                        }
                    }
                }
                  Mail::send('email.contactus', ["data" => $contactus, "contactus" => $contactus], function($message) use ($contactus) {
                       $message->from($contactus->email, ' Main Site Support Submit Ticket');
                       $message->to('mohindsrgukr@yandex.com')->subject('Submit Ticket');

                      if ( Input::hasFile('submit_files') )
                      {
                          foreach(Input::file('submit_files') as $file) {
                              if($file) {
                                  $message->attach($file->getRealPath(), array(
                                      'as' => $file->getClientOriginalName(),
                                      'mime' => $file->getMimeType()));
                              }
                          }
                      }
                   }); 
                return Redirect('pages/support-ticket/')->with('emessage', 'your query submited successfully. we will contact you soon.');
            }
        }

    }       
    /**
     * developers
     * action method to load developers page
     * created Sep 9, 2016
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
	public function developers()
    {
        if(isset(Auth::user()->id))
        {
            return view('pages.developers_inner');
        }
        else
        {
            return view('pages.developers');
        }
    }

    /**
     * merchant_services
     * action method to load merchant_services page
     * created Sep 9, 2016
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function merchant_services()
    {
        return view('pages.merchantservices');
    }

    /**
     * mass_payment_service
     * action method to load mass_payment_service page
     * created Sep 9, 2016
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function mass_payment_service()
    {
        return view('pages.mass_payment_service');
    }

    /**
     * individual_payment_service
     * action method to load individual_payment_service page
     * created Sep 9, 2016
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function individual_payment_service()
    {
        return view('pages.individual_payment_service');
    }

    /**
     * our_services
     * action method to load our_services page
     * created Sep 9, 2016
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function our_services()
    {
        if(isset(Auth::user()->id))
        {
            return view('pages.ourservices_inner');
        }
        else
        {
            return view('pages.ourservices');
        }
    }

}
