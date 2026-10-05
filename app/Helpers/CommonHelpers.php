<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;

class CommonHelpers {

    public static function send_email($view, $data, $to, $subject = 'Welcome !', $from_email = null, $from_name = null) {
        $from_name = $from_name ?? env('APP_NAME', "Gexton INC");
        $from_email = $from_email ?? env('DEFAULT_EMAIL', "info@gexton.com");

        $data = (array) $data;
        $data['subject'] = $subject;
        $data['to'] = $to;
        $data['from_name'] = $from_name;
        $data['from_email'] = $from_email;

        $data['data'] = $data;
        try {
            Mail::send('emails.' . $view, $data, function ($message) use ($data) {
                $message->from($data['from_email'], $data['from_name']);
                $message->subject($data['subject']);
                $message->to($data['to']);
            });
            return true;
        } catch (Exception $ex) {
            return response()->json($ex);
        }
    }

    public static function uploadSingleFile($file, $path = 'upload/images/', $types = "png,gif,csv,jpeg", $filesize = '20000') {
        $path = $path . date('Y') . '/';

        $rules = array('file' => 'required|mimes:' . $types . "|max:" . $filesize);
        $validator = \Validator::make(array('file' => $file), $rules);
        if ($validator->passes()) {
            $rand = time() . "_" . \Str::random(15) . "_";
            $f_name = $rand . $file->getClientOriginalName();
            $filename = $path . "full_" . $f_name;

            //full size image
            $file->storeAs($path, "full_" . $f_name);

            // if ($thumbnail && count($thumbnail) > 0) {
            //     //thumbnail
            //     $thumb = "sm_" . $f_name;
            //     $file->storeAs($path, $thumb);
            //     Self::createThumbnail($path . '/' . $thumb, $thumbnail[0], $thumbnail[1]);
            // }

            return $filename;
        } else {
            return ['error' => $validator->errors()->first('file')];
        }
    }

    public static function createThumbnail($file, $width, $height) {
        $img = \ImageLib::make($file)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($file);
    }

    public static function date_format_custom($date, $month_name = true) {
        $format = 'd-m-Y';
        if ($month_name) {
            $format = 'd M, Y';
        }
        return date($format, strtotime($date));
    }

    public static function time_format_custom($time, $amPm = true) {
        $format = 'H:i';
        if ($amPm) {
            $format = 'h:i A';
        }
        return date($format, strtotime($time));
    }

    public static function date_time_full($dateTime) {
        return date('d M, Y | h:i A', strtotime($dateTime));
    }

    public static function rights($val) {
        if (auth()->user()->is_admin) {
            return true;
        } else {
            return auth()->user()->rights->$val ?? null;
        }
    }

    // controller file get  $path = $request->file('csv_file')->getRealPath(); 
    public static function csvToArray($filename = '', $delimiter = ',') {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    public static function order_records_csv($filename = '', $delimiter = ',', $header = null) {
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $_row = explode('#$#', $row[0]);
               $data[] = array_combine($header, $_row);
            }
            fclose($handle);
        }

        return $data;
    }

    public static function custom_number_format($amount) {
        if (is_float($amount)) {
            $amount = round($amount);   
        }
        return '$' . number_format($amount, 2);
    }

    public static function batchCodeDate($date) {
      return date('dmY', strtotime($date));
    }

}
