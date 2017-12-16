<?php

include('vendor/autoload.php');
include('TelegramBot.php');
include('Weather.php');

$telegramApi = new TelegramBot();

$weatherApi = new Weather();

while (true) {
	sleep(2);
	$updates = $telegramApi->getUpdates();
	print_r($updates);

	foreach ($updates as $update) {

		if (isset($update->message->location)) {
			$result = $weatherApi->getWeather($update->message->location->latitude, $update->message->location->longitude);

			switch ($result->weather[0]->main) {

				case "Clear":
					$response = "На улице безоблачно. Зонтик не нужен. Хорошего дня!";
					break;
				case "Clouds":
					$response = "На улице облачно. Зонтик на всякий случай нужно взять. Хорошего дня!";
					break;
				case "Rain":
					$response = "на улице дождь. Возьмите зонтик! Хорошего дня!";
					break;
				default:
					$response = "Посмотрите в окно и решите сами. Хорошего дня!";
					
			}
			
			$telegramApi->sendMessage($update->message->chat->id, $response);

		} else {
			$telegramApi->sendMessage($update->message->chat->id, 'Отправьте свою локацию!');
		}
	}
}

