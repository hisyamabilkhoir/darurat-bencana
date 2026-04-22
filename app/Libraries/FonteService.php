<?php

namespace App\Libraries;

/**
 * FonteService - Fonnte WhatsApp API Integration
 * 
 * Handles sending WhatsApp messages via Fonnte API
 * API Docs: https://docs.fonnte.com/api-send-message/
 */
class FonteService
{
    /** @var string Fonnte API endpoint */
    private string $apiUrl = 'https://api.fonnte.com/send';

    /** @var string API token from .env */
    private string $token;

    public function __construct()
    {
        // Load token from .env configuration
        $this->token = env('fonnte.token', 'YOUR_FONNTE_TOKEN_HERE');
    }

    /**
     * Send WhatsApp message to a single target
     *
     * @param string $target  Target phone number
     * @param string $message Message content
     * @return array Response from Fonnte API
     */
    public function sendMessage(string $target, string $message): array
    {
        $postFields = [
            'target'      => $target,
            'message'     => $message,
            'countryCode' => '62',
        ];

        return $this->makeRequest($postFields);
    }

    /**
     * Send WhatsApp message to multiple targets (comma-separated)
     *
     * @param string $targets Comma-separated phone numbers
     * @param string $message Message content
     * @return array Response from Fonnte API
     */
    public function sendBulkMessage(string $targets, string $message): array
    {
        $postFields = [
            'target'      => $targets,
            'message'     => $message,
            'delay'       => '2',
            'countryCode' => '62',
        ];

        return $this->makeRequest($postFields);
    }

    /**
     * Send disaster report notification to all active contacts
     *
     * @param array $laporan Report data
     * @return array API response
     */
    public function sendDisasterNotification(array $laporan): array
    {
        // Get all active contact numbers
        $kontakModel = new \App\Models\KontakModel();
        $targets = $kontakModel->getActiveNumbers();

        if (empty($targets)) {
            return [
                'status'  => false,
                'message' => 'Tidak ada kontak aktif untuk dikirim notifikasi.',
            ];
        }

        // Format the notification message
        $message = $this->formatDisasterMessage($laporan);

        // Send to all contacts
        return $this->sendBulkMessage($targets, $message);
    }

    /**
     * Format disaster report into WhatsApp message
     *
     * @param array $laporan Report data
     * @return string Formatted message
     */
    private function formatDisasterMessage(array $laporan): string
    {
        $tanggal = date('d M Y H:i', strtotime($laporan['tanggal']));

        $message = "🚨 *Laporan Bencana Baru*\n\n";
        $message .= "📋 *Kategori:* {$laporan['kategori']}\n";
        $message .= "📍 *Lokasi:* {$laporan['lokasi']}\n";
        $message .= "👤 *Pelapor:* {$laporan['nama_pelapor']}\n";
        $message .= "📝 *Detail:* {$laporan['deskripsi']}\n";
        $message .= "📅 *Tanggal:* {$tanggal}\n\n";
        $message .= "⚠️ _Segera lakukan penanganan!_\n";
        $message .= "━━━━━━━━━━━━━━━━━━━\n";
        $message .= "🔗 Darurat Bencana System";

        return $message;
    }

    /**
     * Execute cURL request to Fonnte API
     *
     * @param array $postFields POST data
     * @return array Decoded response
     */
    private function makeRequest(array $postFields): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $postFields,
            CURLOPT_HTTPHEADER     => [
                'Authorization: ' . $this->token,
            ],
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            log_message('error', 'Fonnte API Error: ' . $error);
            return [
                'status'  => false,
                'message' => 'Gagal mengirim notifikasi WhatsApp: ' . $error,
            ];
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'Fonnte API Invalid Response: ' . $response);
            return [
                'status'  => false,
                'message' => 'Response tidak valid dari Fonnte API.',
            ];
        }

        log_message('info', 'Fonnte API Response: ' . $response);
        return $decoded;
    }
}
