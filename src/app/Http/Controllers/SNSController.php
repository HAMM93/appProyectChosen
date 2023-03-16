<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailHistory;
use Illuminate\Support\Carbon;
use Exception;

class SNSController extends Controller
{
    public function snsNotifications(Request $request)
    {
        $data = $request->json()->all();

        if (isset($data['Type']) && $data['Type'] == 'SubscriptionConfirmation') {
            file_get_contents($data['SubscribeURL']);
        } elseif (isset($data['eventType']) && isset($data['mail'])) {
            $message_id = null;

            foreach($data['mail']['headers'] as $newV) {
                if($newV['name'] == 'Custom-Message-ID') {
                    $message_id = $newV['value'];
                }
            }

            try {
                switch(strtolower($data['eventType'])) {
                    case 'bounce':
                        $email = EmailHistory::where('message_id', $message_id)->first();
                        $email->bounced = Carbon::now()->toDateTimeString();
                        $email->save();
                        break;
                    case 'complaint':
                        $email = EmailHistory::where('message_id', $message_id)->first();
                        $email->complaint = Carbon::now()->toDateTimeString();
                        $email->save();
                        break;
                    case 'open':
                        $email = EmailHistory::where('message_id', $message_id)->first();
                        $email->opened = Carbon::now()->toDateTimeString();
                        $email->save();
                        break;
                    case 'delivery':
                        $email = EmailHistory::where('message_id', $message_id)->first();
                        $email->delivered = Carbon::now()->toDateTimeString();
                        $email->save();
                        break;
                    default:
                        break;
                }
            } catch (Exception $e) {
                //...
            }
        }

        return response('OK', 200);
    }
}
