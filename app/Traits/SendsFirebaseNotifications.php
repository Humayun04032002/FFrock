<?php

namespace App\Traits;

use App\Models\User;
use Google_Client;
use Illuminate\Support\Facades\Log;

trait SendsFirebaseNotifications
{
    /**
     * অ্যাপ এবং ওয়েব উভয় প্ল্যাটফর্মে ছবিসহ নোটিফিকেশন পাঠায়।
     */
    public function sendNotifications(string $title, string $body, ?string $imageUrl = null): void
    {
        // ওয়েবের ব্যবহারকারীদের জন্য নোটিফিকেশন পাঠানো
        $this->sendWebPushNotification($title, $body, $imageUrl);

        // অ্যাপের ব্যবহারকারীদের জন্য নোটিফিকেশন পাঠানো
        $this->sendAppPushNotification($title, $body, $imageUrl);
    }

    /**
     * V1 API ব্যবহার করে ওয়েব পুশ নোটিফিকেশন পাঠায়।
     */
    private function sendWebPushNotification(string $title, string $body, ?string $imageUrl = null): void
    {
        try {
            $accessToken = $this->getAccessToken();
            if (!$accessToken) return;

            $tokens = User::whereNotNull('FCM_Token')->pluck('FCM_Token')->all();
            if (empty($tokens)) return;

            $projectId = env('FIREBASE_PROJECT_ID', 'auto-payment-c2978');
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
            
            foreach ($tokens as $token) {
                // ** ওয়েবের জন্য অপ্টিমাইজড Payload **
                $messagePayload = [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                            'image' => $imageUrl // ওয়েব ব্রাউজারের জন্য সরাসরি 'image' কী কাজ করে
                        ],
                    ]
                ];
                $this->sendCurlRequest($url, $accessToken, $messagePayload);
            }
        } catch (\Exception $e) {
            Log::error("Web Push Error: " . $e->getMessage());
        }
    }
    
    /**
     * V1 API ব্যবহার করে অ্যাপের টপিকে নোটিফিকেশন পাঠায়।
     */
    private function sendAppPushNotification(string $title, string $body, ?string $imageUrl = null): void
    {
        try {
            $accessToken = $this->getAccessToken();
            if (!$accessToken) return;

            $projectId = env('FIREBASE_PROJECT_ID', 'auto-payment-c2978');
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            // ** অ্যাপের জন্য পুরনো এবং নির্ভরযোগ্য Payload **
            // এখানে আমরা শুধু 'notification' অবজেক্ট ব্যবহার করছি, যা আপনার পুরনো সিস্টেম সমর্থন করে।
            $messagePayload = [
                'message' => [
                    'topic' => 'news',
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'image' => $imageUrl // Legacy API-এর মতো সরাসরি ইমেজ যোগ করা হলো
                    ]
                ],
            ];
            
            $this->sendCurlRequest($url, $accessToken, $messagePayload);
        } catch (\Exception $e) {
            Log::error("App Notification Error: " . $e->getMessage());
        }
    }

    /**
     * Google Service Account ব্যবহার করে একটি Access Token তৈরি করে।
     */
    private function getAccessToken(): ?string
    {
        // ... এই মেথডটি অপরিবর্তিত থাকবে ...
        try {
            $credentialsFilePath = base_path(env('FIREBASE_CREDENTIALS'));
            $client = new Google_Client();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            return $client->getAccessToken()['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error("Access Token generation failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * cURL ব্যবহার করে চূড়ান্ত রিকোয়েস্ট পাঠায়।
     */
    private function sendCurlRequest(string $url, string $accessToken, array $payload): void
    {
        // ... এই মেথডটিও অপরিবর্তিত থাকবে ...
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url, CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken, 'Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => json_encode($payload), CURLOPT_TIMEOUT => 10,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}