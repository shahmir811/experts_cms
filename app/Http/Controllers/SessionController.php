<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class SessionController extends Controller
{
    public function index()
    {
        $now = Carbon::now()->timestamp;
        $lifetime = config('session.lifetime') * 60;
        $currentSessionId = session()->getId();

        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.*', 'users.name as user_name')
            ->get()
            ->map(function ($session) use ($now, $lifetime) {
                $data = unserialize(base64_decode($session->payload));
                $loginTime = $data['login_time'] ?? null;

                return [
                    'id' => $session->id,
                    'user_id' => $session->user_id,
                    'user_name' => $session->user_name,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => Carbon::createFromTimestamp($session->last_activity)
                        ->setTimezone(config('app.timezone'))
                        ->format('d F Y h:i a'),
                    'last_activity_unix' => $session->last_activity,
                    'login_time' => $loginTime ? Carbon::parse($loginTime)->format('d F Y h:i a') : 'Unknown',
                    'login_time_unix' => $loginTime ? Carbon::parse($loginTime)->timestamp : 0,

                ];
            })
            ->filter(function ($session) use ($now, $lifetime) {
                return ($now - $session['last_activity_unix']) <= $lifetime;
            })
            ->sortByDesc('login_time_unix')
            ->map(function ($session) use ($currentSessionId) {
                unset($session['last_activity_unix'], $session['login_time_unix']);
                $session['status'] = 'active';
                $session['user_name'] = $session['user_name'] ?? 'Guest';
                $session['is_current'] = $session['id'] === $currentSessionId;
                return (object) $session;
            });

        return view('pages.sessions.index', compact('sessions'));
    }


    public function destroy($id)
    {
        $session = DB::table('sessions')->where('id', $id)->first();
        if ($session) {
            DB::table('sessions')->where('id', $id)->delete();
            return redirect()->route('active-sessions')->with('success', 'Session deleted successfully');
        }
        return redirect()->route('active-sessions')->with('error', 'Session not found');
    }

}
