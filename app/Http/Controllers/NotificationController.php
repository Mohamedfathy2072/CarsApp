<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Services\Firebase\FirebaseNotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private FirebaseNotificationService $fcm;

    public function __construct(FirebaseNotificationService $fcm)
    {
        $this->fcm = $fcm;
    }

    public function send(Request $request)
    {
        $request->validate([
            'send_type' => 'required|in:topic,users',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'topic' => 'required_if:send_type,topic',
            'user_ids' => 'required_if:send_type,users|array',
        ]);

        $dataPayload = ['type' => $request->send_type];

        if ($request->send_type === 'topic') {
            // Send to Topic
            $this->fcm->sendToTopic($request->topic, $request->title, $request->body, $dataPayload);

            Notification::create([
                'title' => $request->title,
                'body' => $request->body,
                'type' => 'topic',
                'sent_at' => now()
            ]);

        } elseif ($request->send_type === 'users') {
            $users = User::whereIn('id', $request->user_ids)->get();

            foreach ($users as $user) {
                // Check if user has fcm_token or whatever token you use
                if ($user->fcm_token) {
                    $this->fcm->sendToToken($user->fcm_token, $request->title, $request->body, $dataPayload);
                }

                Notification::create([
                    'title' => $request->title,
                    'body' => $request->body,
                    'type' => 'individual',
                    'sent_at' => now(),
                    'user_id' => $user->id
                ]);
            }
        }

        return redirect()->back()->with('success', 'تم إرسال الإشعارات بنجاح');
    }

    public function index()
    {
        $notifications = Notification::latest()->paginate(20);

        return view('dashboard.notifications.index', compact('notifications'));
    }


    public function create()
    {
        $users = User::select('id', 'name')->orderBy('name')->get();
        return view('dashboard.notifications.send', compact('users'));
    }
}
