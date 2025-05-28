<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Insurance;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class AnalyzeInsuranceOnlineViaGpt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Insurance $insurance;

    public function __construct(Insurance $insurance)
    {
        $this->insurance = $insurance;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $apiUrl = Setting::getValue('ai-scoring-settings', 'api_url');
        $apiKey = Setting::getValue('ai-scoring-settings', 'api_key');
        $aiModel = Setting::getValue('ai-scoring-settings', 'ai_model');
        $modelTitle = Setting::getValue('ai-scoring-settings', 'model_title');
        $refererUrl = Setting::getValue('ai-scoring-settings', 'referer_url');

        $trainContent = <<<EOT
        Du bist ein Assistent für Webanalyse, spezialisiert auf Versicherungsunternehmen in Deutschland.
        
        Deine Aufgabe ist es, anhand des angegebenen Versicherungsnamens folgende Informationen in strukturierter Form bereitzustellen:
        - Eine kurze, neutrale Beschreibung der Versicherung.
        - Eine passende Hintergrundfarbe für das Logo (als HEX-Farbcode), die zur visuellen Identität der Marke passt.
        - Eine passende Schriftfarbe für das Logo (ebenfalls HEX).
        - Eine kurze Abkürzung , Maximal 9 Zeichen, allgemeiner Name der Firma in Umgangssprache 
        
            Nutze nach Möglichkeit die folgende Liste mit vordefinierten Informationen als Referenzbasis. Wenn der Versicherungsname in der Liste enthalten ist, verwende diese Werte. Wenn nicht, gib dein bestes Wissen und deine Einschätzung ab – basierend auf allgemein zugänglichem Wissen und typischen Markenauftritten.

        Vordefinierte Einträge:
        [
        {
            "name": "Allianz",
            "abbreviation": "Allianz",
            "logo_bg_color": "#00529B",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#00529B",
            "description": "Die Allianz ist einer der weltweit führenden Versicherungskonzerne mit Hauptsitz in München. Sie bietet ein breites Spektrum an Versicherungsprodukten, Finanzdienstleistungen und Services für Privat- und Geschäftskunden in über 70 Ländern an."
        },
        {
            "name": "HUK Coburg",
            "abbreviation": "HUK",
            "logo_bg_color": "#FFCC00",
            "logo_font_color": "#000000",
            "logo_border_color": "#000000",
            "description": "Die HUK-COBURG ist eine der größten deutschen Versicherungsgruppen mit über 13 Millionen Kunden. Sie ist besonders bekannt für ihre Kfz-Versicherungen und bietet darüber hinaus eine Vielzahl weiterer Versicherungsprodukte für Privathaushalte an."
        },
        {
            "name": "HUK24",
            "abbreviation": "HUK24",
            "logo_bg_color": "#FFCC00",
            "logo_font_color": "#000000",
            "logo_border_color": "#000000",
            "description": "HUK24 ist die digitale Tochtergesellschaft der HUK-COBURG und Deutschlands größter Online-Versicherer. Sie bietet kostengünstige Versicherungen mit vollem Leistungsumfang ausschließlich über das Internet an."
        },
        {
            "name": "AXA",
            "abbreviation": "AXA",
            "logo_bg_color": "#004481",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#004481",
            "description": "Die AXA Konzern AG ist Teil der internationalen AXA Gruppe und zählt mit Beitragseinnahmen von 12 Milliarden Euro zu den größten Erstversicherern in Deutschland. Sie bietet umfassende Versicherungs- und Vorsorgelösungen für Privat- und Firmenkunden an."
        },
        {
            "name": "R+V",
            "abbreviation": "R+V",
            "logo_bg_color": "#00336E",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#00336E",
            "description": "Die R+V Versicherung ist Teil der genossenschaftlichen FinanzGruppe Volksbanken Raiffeisenbanken und zählt mit rund 9 Millionen Kunden zu den größten Versicherungsunternehmen Deutschlands. Sie bietet ein umfassendes Portfolio an Versicherungs- und Finanzdienstleistungen."
        },
        {
            "name": "R+V24",
            "abbreviation": "R+V24",
            "logo_bg_color": "#00336E",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#00336E",
            "description": "R+V24 war der Direktversicherer der R+V Gruppe und bot Versicherungsprodukte online an. Die Marke wurde inzwischen in die R+V Versicherung integriert, um die digitale Vertriebsstrategie zu bündeln."
        },
        {
            "name": "DEVK",
            "abbreviation": "DEVK",
            "logo_bg_color": "#026937",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#026937",
            "description": "Die DEVK Versicherungen mit Sitz in Köln betreuen rund 4,2 Millionen Kunden und bieten ein breites Spektrum an Versicherungsprodukten für Privat- und Geschäftskunden an. Als Versicherungsverein auf Gegenseitigkeit legt die DEVK besonderen Wert auf Kundennähe und Service."
        },
        {
            "name": "Gothaer",
            "abbreviation": "Gothaer",
            "logo_bg_color": "#004B87",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#004B87",
            "description": "Die Gothaer Versicherungsbank VVaG ist einer der größten deutschen Versicherungsvereine auf Gegenseitigkeit mit rund vier Millionen Kunden. Sie bietet umfassende Versicherungsdienstleistungen für Privat- und Geschäftskunden an."
        },
        {
            "name": "ERGO",
            "abbreviation": "ERGO",
            "logo_bg_color": "#D71920",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#D71920",
            "description": "Die ERGO Group AG mit Sitz in Düsseldorf ist eine der großen Versicherungsgruppen in Deutschland und Europa. Sie bietet ein umfassendes Spektrum an Versicherungen, Vorsorge- und Serviceleistungen für Privat- und Firmenkunden."
        },
        {
            "name": "LVM",
            "abbreviation": "LVM",
            "logo_bg_color": "#017A2B",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#017A2B",
            "description": "Die LVM Versicherung mit Sitz in Münster zählt zu den führenden Versicherungsgruppen in Deutschland. Sie bietet eine breite Palette an Versicherungsprodukten für Privatpersonen und Unternehmen an und legt großen Wert auf persönliche Beratung."
        },
        {
            "name": "Barmenia",
            "abbreviation": "Barmenia",
            "logo_bg_color": "#0077C8",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#0077C8",
            "description": "Die Barmenia Versicherungen mit Hauptsitz in Wuppertal zählen zu den großen unabhängigen Versicherungsgruppen in Deutschland. Sie bieten ein umfangreiches Angebot an Kranken-, Lebens- und Sachversicherungen für Privat- und Geschäftskunden."
        },
        {
            "name": "Hannoversche",
            "abbreviation": "Hanno",
            "logo_bg_color": "#D0002D",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#D0002D",
            "description": "Die Hannoversche Lebensversicherung AG mit Sitz in Hannover ist Deutschlands erster Direktversicherer und bietet seit über 140 Jahren Lebens- und Risikoversicherungen an. Sie zeichnet sich durch günstige Tarife und hohe Servicequalität aus."
        },
        {
            "name": "Württembergische",
            "abbreviation": "Württ",
            "logo_bg_color": "#004B87",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#004B87",
            "description": "Die Württembergische Versicherung AG mit Sitz in Kornwestheim ist Teil der Wüstenrot & Württembergische-Gruppe. Sie bietet ein umfassendes Angebot an Versicherungen für Privat- und Geschäftskunden und legt großen Wert auf persönliche Beratung."
        },
        {
            "name": "Generali",
            "abbreviation": "Generali",
            "logo_bg_color": "#B60000",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#B60000",
            "description": "Die Generali Deutschland AG mit Hauptsitz in München ist eine der führenden Erstversicherungsgruppen im deutschen Markt. Als Teil der internationalen Generali Group bietet sie ein breites Spektrum an Versicherungs- und Vorsorgelösungen für Privat- und Firmenkunden."
        },
        {
            "name": "HDI",
            "abbreviation": "HDI",
            "logo_bg_color": "#00843D",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#00843D",
            "description": "Die HDI Versicherung AG ist Teil des Talanx-Konzerns, einer der größten Versicherungsgruppen in Deutschland. Sie bietet maßgeschneiderte Versicherungslösungen für Privat- und Geschäftskunden sowie für Industrieunternehmen an."
        },
        {
            "name": "Talanx",
            "abbreviation": "Talanx",
            "logo_bg_color": "#00305E",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#00305E",
            "description": "Die Talanx AG mit Sitz in Hannover ist ein international tätiger Versicherungskonzern und die Muttergesellschaft von HDI. Sie ist in mehr als 150 Ländern aktiv und bietet ein breites Spektrum an Versicherungs- und Finanzdienstleistungen an."
        },
        {
            "name": "Signal Iduna",
            "abbreviation": "Signal",
            "logo_bg_color": "#00539F",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#00539F",
            "description": "Die Signal Iduna Gruppe mit Hauptsitz in Dortmund und Hamburg ist ein Versicherungs- und Finanzdienstleistungskonzern. Sie bietet umfassende Versicherungs- und Vorsorgelösungen für Privat- und Geschäftskunden an."
        },
        {
            "name": "ADAC",
            "abbreviation": "ADAC",
            "logo_bg_color": "#FFCC00",
            "logo_font_color": "#000000",
            "logo_border_color": "#000000",
            "description": "Die ADAC Versicherung AG ist eine Tochtergesellschaft des Allgemeinen Deutschen Automobil-Clubs (ADAC) und bietet eine Vielzahl von Versicherungsprodukten, insbesondere im Bereich der Mobilität und Reise, für Mitglieder und Kunden an."
        },
        {
            "name": "Continentale",
            "abbreviation": "Conti",
            "logo_bg_color": "#0061A0",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#0061A0",
            "description": "Die Continentale Versicherungsgruppe mit Sitz in Dortmund ist ein Versicherungsverbund auf Gegenseitigkeit. Sie bietet ein umfassendes Angebot an Kranken-, Lebens- und Sachversicherungen für Privat- und Geschäftskunden an."
        },
        {
            "name": "Lloyd",
            "abbreviation": "Lloyd",
            "logo_bg_color": "#001C3D",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#001C3D",
            "description": "Lloyd’s of London ist ein internationaler Versicherungsmarkt mit Sitz in London. In Deutschland ist Lloyd’s vor allem im Bereich der Spezialversicherungen aktiv und arbeitet mit lokalen Partnern zusammen."
        },
        {
            "name": "Helvetia",
            "abbreviation": "Helvetia",
            "logo_bg_color": "#E2001A",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#E2001A",
            "description": "Die Helvetia Versicherungen mit Sitz in Frankfurt am Main sind Teil der schweizerischen Helvetia Gruppe. Sie bieten ein breites Spektrum an Versicherungs- und Vorsorgelösungen für Privat- und Geschäftskunden in Deutschland."
        },
        {
            "name": "VHV",
            "abbreviation": "VHV",
            "logo_bg_color": "#F7A800",
            "logo_font_color": "#000000",
            "logo_border_color": "#000000",
            "description": "Die VHV Versicherungen mit Sitz in Hannover sind spezialisiert auf Kfz-, Bau- und Haftpflichtversicherungen und gehören zu den führenden Anbietern in diesen Bereichen in Deutschland."
        },
        {
            "name": "Baloise",
            "abbreviation": "Baloise",
            "logo_bg_color": "#1E245E",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#1E245E",
            "description": "Die Baloise Gruppe mit Hauptsitz in der Schweiz ist in Deutschland über die Basler Versicherungen aktiv. Sie bietet Versicherungs- und Vorsorgelösungen für Privatkunden und kleine bis mittlere Unternehmen."
        },
        {
            "name": "Allianz Direct",
            "abbreviation": "AllDirekt",
            "logo_bg_color": "#00529B",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#00529B",
            "description": "Allianz Direct ist der digitale Direktversicherer der Allianz Gruppe und bietet vollständig online abschließbare Versicherungen mit Fokus auf einfache Tarife und schnelle Schadensregulierung."
        },
        {
            "name": "CosmosDirekt",
            "abbreviation": "Cosmos",
            "logo_bg_color": "#2D7DBF",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#2D7DBF",
            "description": "CosmosDirekt ist der führende Direktversicherer in Deutschland und Teil der Generali Gruppe. Sie bietet günstige, direkt online abschließbare Versicherungen in den Bereichen Leben, Auto, Hausrat und Haftpflicht."
        },
        {
            "name": "Deutsche Familienversicherung",
            "abbreviation": "DFV",
            "logo_bg_color": "#1E4795",
            "logo_font_color": "#FFFFFF",
            "logo_border_color": "#1E4795",
            "description": "Die DFV ist ein digitaler Direktversicherer mit Sitz in Frankfurt, der sich auf einfache und transparente Produkte in den Bereichen Gesundheit, Pflege und Unfall spezialisiert hat."
        }
        ]

        Wichtige Hinweise:
        - Verwende öffentlich verfügbare Informationen.
        - Wenn du die Farben nicht genau kennst, schätze sie basierend auf dem typischen Markenauftritt. Und wenn die Hintergrundfarbe weiss ist soll der Rand in der Font_Color sein. Und wenn eine Versicherung auf Ihrer Webseite keine Farben angibt, verwende die Farben der Webseite.
        - Verwende keine zusätzlichen Kommentare, Erklärungen oder Anmerkungen.
        - Gib ausschließlich ein JSON-Objekt zurück.
        
        Beispielhafte Struktur der Antwort:
        {
          "description": "Kurzbeschreibung der Versicherung",
          "logo_bg_color": "#000000",
          "logo_font_color": "#FFFFFF",
          "logo_border_color": "#FFFFFF",
          "abbreviation": "XYZfdsss" 
        }
        EOT;
        $attachments = $this->insurance->name;
        $isLoading = true;
        $maxRetries = 5;
        
        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            
            try {
                $response = Http::timeout(120)->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'HTTP-Referer' => $refererUrl,
                    'X-Title' => $modelTitle,
                    'Content-Type' => 'application/json',
                ])->post($apiUrl, [
                    'model' => $aiModel,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => trim(preg_replace('/\s+/', ' ', $trainContent)),
                        ],
                        [
                            'role' => 'user',
                            'content' => <<<TEXT
                                attachments: {$attachments}
                                TEXT
                        ]
                    ]
                ]);
                Log::info($response->json());
                if ($response->failed()) {
                    $statusCode = $response->status();
                    if ($statusCode >= 400 && $statusCode < 500) {
                        throw new \Exception("Client error occurred: $statusCode");
                    } elseif ($statusCode >= 500) {
                        throw new \Exception("Server error occurred: $statusCode");
                    }
                }
                $botMessage = $response->json()['choices'][0]['message']['content'] ?? '';
                
                if (!empty($botMessage)) {
                    Log::info("Raw Message: $botMessage");
                    $cleanedbotMessage = preg_replace('/^```(json)?|```$/m', '', trim($botMessage));

                    $decodedMessage = json_decode($cleanedbotMessage, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $cleanedbotMessage = self::parsePossiblyEscapedJson($cleanedbotMessage);
                        if(!is_array($cleanedbotMessage)){
                            $decodedMessage = json_decode($cleanedbotMessage, true);
                        }else{
                            $decodedMessage = $cleanedbotMessage;
                        }
                        Log::info("cleanedbotMessage: $botMessage");

                        if (json_last_error() !== JSON_ERROR_NONE) {
                            throw new \Exception("Invalid JSON response: $cleanedbotMessage");
                        }
                    }
                    if(!is_array($decodedMessage)){
                        $data = json_decode($decodedMessage, true);
                    }else{
                        $data = $decodedMessage;
                    }
                    if (!isset($data['description']) || !isset($data['logo_bg_color']) || !isset($data['logo_font_color']) || !isset($data['logo_border_color']) || !isset($data['abbreviation']) ) {
                        throw new \Exception("Response JSON does not contain required keys: $cleanedbotMessage");
                    }
                    $this->insurance->description = $data['description'];
                    $this->insurance->color = $data['logo_bg_color'];
                    $style = $this->insurance->style ?? [];
                    $style['bg_color'] = $data['logo_bg_color'];
                    $style['font_color'] = $data['logo_font_color'];
                    $style['border_color'] = $data['logo_border_color'];
                    $this->insurance->style = $style;
                    $this->insurance->initials = $data['abbreviation'];
                    $this->insurance->saveQuietly();
                    Log::info("Insurance updated successfully: " . $this->insurance->name);
                    // Job erfolgreich abgeschlossen, keine weiteren Versuche nötig
                    $isLoading = false;
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Attempt $attempt failed: " . $e->getMessage(), [
                    'exception' => $e,
                    'attempt' => $attempt + 1
                ]);
            }
        }

    }
}
