<?php

namespace App\AppRinger;
use SKAgarwal\GoogleApi\PlacesApi;
use App\Config\AppConfig;

class ChatGPT
{
	//https://github.com/p4us/ChatGPT-PHP
    public static function getAIDescription($command)
    {
        $dTemperature = 0.9;
		$iMaxTokens = 1500;
		$top_p = 1;
		$frequency_penalty = 0.0;
		$presence_penalty = 0.0;
		$OPENAI_API_KEY = AppConfig::getChatGPTApiKey();
		$sModel = "text-davinci-003";
		$prompt = $command;
		$ch = curl_init();
		$headers  = [
		    'Accept: application/json',
		    'Content-Type: application/json',
		    'Authorization: Bearer ' . $OPENAI_API_KEY . ''
		];

		$postData = [
		    'model' => $sModel,
		    'prompt' => str_replace('"', '', $prompt),
		    'temperature' => $dTemperature,
		    'max_tokens' => $iMaxTokens,
		    'top_p' => $top_p,
		    'frequency_penalty' => $frequency_penalty,
		    'presence_penalty' => $presence_penalty,
		    'stop' => '[" Human:", " AI:"]',
		];

		curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/completions');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

		$result = curl_exec($ch);
		$decoded_json = json_decode($result, true);

		return $decoded_json['choices'][0]['text'];
    }
}
