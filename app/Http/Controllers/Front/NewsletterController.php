<?php

namespace App\Http\Controllers\Front;

use App\Events\SubscribeNewsletterConfirmed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use App\Events\SubscribeNewsletterSave;

class NewsletterController extends Controller
{

    private $member;

    public function __construct(Repositories\MemberRepositoryEloquent $member){

        $this->member = $member;

    }


    public function subscribeNewsleterMember(Request $request){

        $std = new \stdClass();

        switch($request->get('action')){

            case 'not_exist':

                $member = $this->member->create([
                    'email' => $request->get('email'),
                    'name'=>$request->get('name'),
                    'surname'=>$request->get('surname'),
                    'newsletter' => 0,
                    'rodo'=>1,
                    'verification_token'=>str_slug(bcrypt($request->get('email')),'')
                ]);

                $std->raport = 'do_confirm';
                $std->message = __('translations.confirm_newsletter_subscribe');

                event(new SubscribeNewsletterSave($member));

                break;

            case 'exist_not_subscribe':

                $member = $this->member->findWhere(['email'=>$request->get('email')])->first()->update(['newsletter'=>0,'name'=>$request->get('name'),
                    'surname'=>$request->get('surname'),'rodo'=>1]);

                $std->raport = 'do_confirm';
                $std->message = __('translations.confirm_newsletter_subscribe');

                event(new SubscribeNewsletterSave($member));

                break;

            case 'exist_not_confirmed_subscribe':

                $member = $this->member->findWhere(['email'=>$request->get('email')])->first();

                $std->raport = 'do_confirm_next_time';
                $std->message = __('translations.mess');

                event(new SubscribeNewsletterSave($member));

                break;

        }


        return response(\GuzzleHttp\json_encode($std),200,['Content-type'=>'application/json']);
    }


    public function subscribeConfirm($prefix, $token){

        $std = new \stdClass();

        $member = $this->member->findWhere(['verification_token'=>$token])->first();
        

        if(!is_null($member)){

            $this->member->update(
                [
                    'newsletter'=>1
                ]
            , $member->id);

            $std->message = __('translations.confirm_success');

            event(new SubscribeNewsletterConfirmed($member));

        }else{
            $std->message = __('translations.confirm_error');
        }


        return $std->message;

    }


    public function unsubscribeNewsletterMember($prefix, $token){

        $member = $this->member->findWhere(['verification_token'=>$token])->first();
        $this->member->update(
            [
                'newsletter'=>-1
            ]
            , $member->id);

    }


    public function checkIsEmailInBaseForNewsletter(Request $request){

        $std = new \stdClass();
        $mbr = $this->member->findWhere(['email'=>$request->get('email')]);

        if(count($mbr)==0){
            $std->success = true;
            $std->status = 'not_exist';
            $std->message = __('translations.confirm_newsletter_subscribe');
            return response(\GuzzleHttp\json_encode($std),200,['Content-type'=>'application/json']);
        }else{

            if($mbr->first()->newsletter==-1){
                $std->success = true;
                $std->status = 'exist_not_subscribe';
                $std->message = __('translations.confirmed_newsletter_subscribe');
                return response(\GuzzleHttp\json_encode($std),200,['Content-type'=>'application/json']);
            }

            if($mbr->first()->newsletter==0){
                $std->success = true;
                $std->status = 'exist_not_confirmed_subscribe';
                $std->message = __('translations.confirm_newsletter_subscribe');
                return response(\GuzzleHttp\json_encode($std),200,['Content-type'=>'application/json']);
            }

            if($mbr->first()->newsletter==1) {
                $std->success = false;
                $std->status = 'exist_subscribe';
                $std->message = __('translations.newsletter_confirmed_subscribe');
                return response(\GuzzleHttp\json_encode($std),200,['Content-type'=>'application/json']);
            }
        }

        $std->success = false;

        return response(\GuzzleHttp\json_encode($std),200,['Content-type'=>'application/json']);
    }


}
