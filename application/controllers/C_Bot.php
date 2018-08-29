<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_Bot extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}

	public function index()
	{
		$this->load->library('telegram');
		$this->load->model('m_bot');
		// Set the bot TOKEN
		$bot_token = '593593994:AAGInR0jnWYEmnm_pF5bx5AMF-a8bUMVO28';
		// Instances the class
		$telegram = new Telegram($bot_token);

		/* If you need to manually take some parameters
		*  $result = $telegram->getData();
		*  $text = $result["message"] ["text"];
		*  $chat_id = $result["message"] ["chat"]["id"];
		*/

		// Take text and chat_id from the message
		$type_chat = $telegram->messageFromGroup();
		$text = $telegram->Text();
		$chat_id = $telegram->ChatID();
		$message_id = $telegram->MessageID();
		$user_id = $telegram->UserID();
		$username = $telegram->Username();
		$first_name = $telegram->FirstName();
		$last_name = $telegram->LastName();
		// Test CallBack
		$callback_query = $telegram->Callback_Query();
		if ($callback_query !== null && $callback_query != '') {
			$pilihan = $telegram->Callback_Data();
			if($pilihan == 'loadkra'){
				$url = "http://203.148.85.170:1888/C_Cek/cekkra";
			}elseif($pilihan == 'loadmdn'){
				$url = "http://203.148.85.170:1888/C_Cek/cekmdn";
			}elseif($pilihan == 'loadsmg'){
				$url = "http://203.148.85.170:1888/C_Cek/ceksmg";
			}elseif($pilihan == 'loadmlg1'){
				$url = "http://203.148.85.170:1888/C_Cek/cekmlg1";
			}elseif($pilihan == 'loadmlg2'){
				$url = "http://203.148.85.170:1888/C_Cek/cekmlg2";
			}elseif($pilihan == 'hddkra'){
				$url = "http://203.148.85.170:1888/C_Cek/hddkra";
			}elseif($pilihan == 'hddsmg'){
				$url = "http://203.148.85.170:1888/C_Cek/hddsmg";
			}elseif($pilihan == 'hddmdn'){
				$url = "http://203.148.85.170:1888/C_Cek/hddmdn";
			}elseif($pilihan == 'hddmlg1'){
				$url = "http://203.148.85.170:1888/C_Cek/hddmlg1";
			}elseif($pilihan == 'hddmlg2'){
				$url = "http://203.148.85.170:1888/C_Cek/hddmlg2";
			}elseif($pilihan == 'memokra'){
				$url = "http://203.148.85.170:1888/C_Cek/memokra";
			}elseif($pilihan == 'memomdn'){
				$url = "http://203.148.85.170:1888/C_Cek/memomdn";
			}elseif($pilihan == 'memosmg'){
				$url = "http://203.148.85.170:1888/C_Cek/memosmg";
			}elseif($pilihan == 'memomlg1'){
				$url = "http://203.148.85.170:1888/C_Cek/memomlg1";
			}elseif($pilihan == 'memomlg2'){
				$url = "http://203.148.85.170:1888/C_Cek/memomlg2";
			}elseif($pilihan == 'datafifdc'){
		        $url = "http://203.148.85.170:1888/C_Cek/cekDataFIFDC";
		    }
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
			// This is what solved the issue (Accepting gzip encoding)
			curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
			$response = curl_exec($ch);
			curl_close($ch);
		    $reply = 'Callback value '.$telegram->Callback_Data();
		    $content = ['chat_id' => $telegram->Callback_ChatID(), 'text' => $response];
		    $telegram->sendMessage($content);

		    $content = ['callback_query_id' => $telegram->Callback_ID(), 'text' => $response];
		    $telegram->answerCallbackQuery($content);
		}

		//Test Inline
		$data = $telegram->getData();
		if ($data['inline_query'] !== null && $data['inline_query'] != '') {
		    $query = $data['inline_query']['query'];
		    // GIF Examples
		    if (strpos('testText', $query) !== false) {
		        $results = json_encode([['type' => 'gif', 'id'=> '1', 'gif_url' => 'http://i1260.photobucket.com/albums/ii571/LMFAOSPEAKS/LMFAO/113481459.gif', 'thumb_url'=>'http://i1260.photobucket.com/albums/ii571/LMFAOSPEAKS/LMFAO/113481459.gif']]);
		        $content = ['inline_query_id' => $data['inline_query']['id'], 'results' => $results];
		        $reply = $telegram->answerInlineQuery($content);
		    }

		    if (strpos('dance', $query) !== false) {
		        $results = json_encode([['type' => 'gif', 'id'=> '1', 'gif_url' => 'https://media.tenor.co/images/cbbfdd7ff679e2ae442024b5cfed229c/tenor.gif', 'thumb_url'=>'https://media.tenor.co/images/cbbfdd7ff679e2ae442024b5cfed229c/tenor.gif']]);
		        $content = ['inline_query_id' => $data['inline_query']['id'], 'results' => $results];
		        $reply = $telegram->answerInlineQuery($content);
		    }
		}

		// Check if the text is a command
		if (!is_null($text) && !is_null($chat_id)) {
			//$cek_priv = $this->m_bot->cek_priv($user_id);
			$perintah = preg_split('/\s+/', $text);
		    if ($text == '/test') {
		        if ($telegram->messageFromGroup()) {
		            $reply = 'Chat Group';
		        } else {
		            $reply = 'Private Chat';
		        }
		        // Create option for the custom keyboard. Array of array string
		        $option = [['A', 'B'], ['C', 'D']];
		        // Get the keyboard
		        $keyb = $telegram->buildKeyBoard($option);
		        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $reply];
		        $telegram->sendMessage($content);
		    } elseif ($perintah[0] == '/reg' && $type_chat == FALSE) {
		        $insert_reg = $this->m_bot->register($user_id,$username,$perintah[1]);
		        if($insert_reg){
		        $reply = 'Register Sukses';
		        // Build the reply array
		        $content = ['chat_id' => $chat_id, 'text' => $reply];
		        $telegram->sendMessage($content);	
		        }else{
		        $content = ['chat_id' => $chat_id, 'text' => 'Register Gagal'];
		        $telegram->sendMessage($content);
		        }

		    } elseif ($text == '/git') {
		        $reply = 'Check me on GitHub: https://github.com/Eleirbag89/TelegramBotPHP';
		        // Build the reply array
		        $content = ['chat_id' => $chat_id, 'text' => $reply];
		        $telegram->sendMessage($content);
		    } elseif ($text == '/user') {
		        //$reply = 'Check me on GitHub: https://github.com/Eleirbag89/TelegramBotPHP';
		        // Build the reply array
		        $content = ['chat_id' => $chat_id, 'text' => $user_id.' '.$username.' '.$first_name.' '.$last_name];
		        $telegram->sendMessage($content);
		    } elseif ($text == '/img') {
		        // Load a local file to upload. If is already on Telegram's Servers just pass the resource id
		        $img = curl_file_create('test.png', 'image/png');
		        $content = ['chat_id' => $chat_id, 'photo' => $img];
		        $telegram->sendPhoto($content);
		        //Download the file just sended
		        $file_id = $message['photo'][0]['file_id'];
		        $file = $telegram->getFile($file_id);
		        $telegram->downloadFile($file['result']['file_path'], './test_download.png');
		    } elseif ($text == '/where') {
		        // Send the Catania's coordinate
		        $content = ['chat_id' => $chat_id, 'latitude' => '37.5', 'longitude' => '15.1'];
		        $telegram->sendLocation($content);
		    } elseif ($text == '/cekload' || $text == '/cekload@beyondcek_bot') {
		        // Shows the Inline Keyboard and Trigger a callback on a button press
		        $option = [
		                [
		                $telegram->buildInlineKeyBoardButton('Kranggan', $url = '', $callback_data = 'loadkra'),
		                $telegram->buildInlineKeyBoardButton('Semarang', $url = '', $callback_data = 'loadsmg'),
		                $telegram->buildInlineKeyBoardButton('Medan', $url = '', $callback_data = 'loadmdn'),
		                ],
		                [
		                $telegram->buildInlineKeyBoardButton('Malang 1', $url = '', $callback_data = 'loadmlg1'),
		                $telegram->buildInlineKeyBoardButton('Malang 2', $url = '', $callback_data = 'loadmlg2'),
		                ],
		            ];

		        $keyb = $telegram->buildInlineKeyBoard($option);
		        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'Silakan Pilih Server'];
		        $telegram->sendMessage($content);
		    } elseif ($text == '/cekhdd' || $text == '/cekhdd@beyondcek_bot') {
		        // Shows the Inline Keyboard and Trigger a callback on a button press
		        $option = [
		                [
		                $telegram->buildInlineKeyBoardButton('Kranggan', $url = '', $callback_data = 'hddkra'),
		                $telegram->buildInlineKeyBoardButton('Semarang', $url = '', $callback_data = 'hddsmg'),
		                $telegram->buildInlineKeyBoardButton('Medan', $url = '', $callback_data = 'hddmdn'),
		                ],
		                [
		                $telegram->buildInlineKeyBoardButton('Malang 1', $url = '', $callback_data = 'hddmlg1'),
		                $telegram->buildInlineKeyBoardButton('Malang 2', $url = '', $callback_data = 'hddmlg2'),
		                ],
		            ];

		        $keyb = $telegram->buildInlineKeyBoard($option);
		        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'Silakan Pilih Server'];
		        $telegram->sendMessage($content);
		    } elseif ($text == '/cekmemory' || $text == '/cekmemory@beyondcek_bot') {
		        // Shows the Inline Keyboard and Trigger a callback on a button press
		        $option = [
		                [
		                $telegram->buildInlineKeyBoardButton('Kranggan', $url = '', $callback_data = 'memokra'),
		                $telegram->buildInlineKeyBoardButton('Semarang', $url = '', $callback_data = 'memosmg'),
		                $telegram->buildInlineKeyBoardButton('Medan', $url = '', $callback_data = 'memomdn'),
		                ],
		                [
		                $telegram->buildInlineKeyBoardButton('Malang 1', $url = '', $callback_data = 'memomlg1'),
		                $telegram->buildInlineKeyBoardButton('Malang 2', $url = '', $callback_data = 'memomlg2'),
		                ],
		            ];

		        $keyb = $telegram->buildInlineKeyBoard($option);
		        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'Silakan Pilih Server'];
		        $telegram->sendMessage($content);
		    } elseif ($text == '/cekdata' || $text == '/cekdata@beyondcek_bot') {
		        // Shows the Inline Keyboard and Trigger a callback on a button press
		        $option = [
		                [
		                $telegram->buildInlineKeyBoardButton('FIF DC', $url = '', $callback_data = 'datafifdc'),
		                ]
		            ];

		        $keyb = $telegram->buildInlineKeyBoard($option);
		        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'Silakan Pilih Aplikasi'];
		        $telegram->sendMessage($content);
		    }  
		}
	}

}

/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */