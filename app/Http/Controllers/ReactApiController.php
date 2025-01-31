<?php

namespace App\Http\Controllers;

use App\Helpers\CRM;
use App\Jobs\GetLocationAccessToken;
use App\Jobs\UploadContactImage;
use App\Models\CrmToken;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Log;

class ReactApiController extends Controller
{

    public function ssoTokenVerify(Request $request)
    {
        try {
            $ssoKey = env('SSO_KEY', null);
            if (!$ssoKey) {
                return response()->json(['status' => false, 'message' => 'SSO key is not configured.']);
            }

            $decrypted = self::decryptToken($request->ssoToken, $ssoKey);

            if ($decrypted === false) {
                return response()->json(['status' => false]);
            } else {

                $decrypted_data = json_decode($decrypted, true);
                $location_id = isset($decrypted_data['activeLocation']) ? $decrypted_data['activeLocation'] : null;
                $user = User::where('location', $location_id)
                    ->first();

                if (!$user) {
                    return response()->json(['status' => false, 'message' => "User location not found"]);
                }

                if ($user) {
                    $userId = $user->id;
                    $token = CrmToken::where(['location_id' => $location_id])->first();
                    if ($token) {
                        $expireIn = $token->expires_in;
                        $expirationTime = Carbon::parse($token->updated_at)->addSeconds($expireIn);

                        // Check if the token is expired
                        if (Carbon::now()->greaterThanOrEqualTo($expirationTime)) {
                            // Token has expired - refresh it

                            if ($user->separate_location == 0) {
                                $this->getLocAccessTokenJobCall($userId, $location_id, 'viaAgency');
                            } else {
                                $this->getLocAccessTokenJobCall($userId, $location_id, 'Location');
                            }

                            // CRM::getLocationAccessToken($user, $location_id);
                            return response()->json(['status' => true, 'message' => 'Token refreshed'], 200);
                        }
                        return response()->json(['status' => true, 'message' => 'Token exists'], 200);
                    } else {

                        if ($user->separate_location == 0) {
                            $this->getLocAccessTokenJobCall($userId, $location_id, 'viaAgency');
                        } else {
                            $this->getLocAccessTokenJobCall($userId, $location_id, 'Location');
                        }


                        // CRM::getLocationAccessToken($user, $location_id);
                        return response()->json(['status' => true, 'message' => 'Token Created'], 200);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('SSO Decryption Error: ' . $e->getMessage());
        }
        return response()->json(['status' => false, 'message' => 'An error occurred while processing your request.']);
    }


    private function getLocAccessTokenJobCall($userId, $locaId, $type)
    {
        GetLocationAccessToken::dispatch($userId, $locaId, $type)->onQueue(env('JOB_QUEUE_TYPE'));
    }

    private function decryptToken($ssoToken, $ssoKey)
    {
        $ciphertext = base64_decode($ssoToken);

        if (substr($ciphertext, 0, 8) !== "Salted__") {
            return response()->json(['status' => false]);
        }
        $salt = substr($ciphertext, 8, 8);
        $ciphertext = substr($ciphertext, 16);
        list($key, $iv) = self::evp_bytes_to_key($ssoKey, $salt);
        $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

    private function evp_bytes_to_key($password, $salt)
    {
        $key = '';
        $iv = '';
        $derived_bytes = '';
        $previous = '';
        // Concatenate MD5 results until we generate enough key material (32 bytes key + 16 bytes IV = 48 bytes)
        while (strlen($derived_bytes) < 48) {
            $previous = md5($previous . $password . $salt, true);
            $derived_bytes .= $previous;
        }
        // Split the derived bytes into the key (first 32 bytes) and IV (next 16 bytes)
        $key = substr($derived_bytes, 0, 32);
        $iv = substr($derived_bytes, 32, 16);

        return [$key, $iv];
    }

    public function searchContact(Request $request)
    {
        // \Log::info($request->all());
        $ssoKey = env('SSO_KEY', null);
        if (!$ssoKey) {
            return response()->json(['status' => false, 'message' => 'SSO key is not configured.']);
        }
        $decrypted = self::decryptToken($request->ssoToken, $ssoKey);
        if ($decrypted === false) {
            return response()->json(['status' => false]);
        } else {
            $decrypted_data = json_decode($decrypted, true);
            $location_id = isset($decrypted_data['activeLocation']) ? $decrypted_data['activeLocation'] : null;
            $user = User::where('location', $location_id)->first();
            if (!$user) {
                return response()->json(['status' => false, 'message' => "User location not found"]);
            }

            if ($user) {

                $token = CrmToken::where(['location_id' => $location_id])->first();
                $url = 'contacts/search';

                $data = [
                    "locationId" => $location_id,
                    "page" => 1,
                    "pageLimit" => 10,
                    // $payload
                    "filters" => [
                        [
                            "field" => $request->field,
                            "operator" => $request->operator,
                            "value" => $request->value,
                        ]
                    ]
                ];
                $res = CRM::crmV2Loc($user->id, $location_id, $url, 'POST', $data, $token);
                if ($res && property_exists($res, 'contacts')) {
                    return response()->json(['status' => true, 'contacts' => $res->contacts]);
                }
                return response()->json(['status' => false, 'contacts' => 'Something went wrong - no data found']);
            }
        }
    }


    public function uploadContactImage(Request $request)
    {
        try {
            \Log::info($request->all());
            $ssoKey = env('SSO_KEY', null);
            if (!$ssoKey) {
                return response()->json(['status' => false, 'message' => 'SSO key is not configured.']);
            }

            $decrypted = self::decryptToken($request->ssoToken, $ssoKey);
            if ($decrypted === false) {
                return response()->json(['status' => false]);
            } else {
                $decrypted_data = json_decode($decrypted, true);
                $location_id = isset($decrypted_data['activeLocation']) ? $decrypted_data['activeLocation'] : null;
                $user = User::where('location', $location_id)->first();
                if (!$user) {
                    return response()->json(['status' => false, 'message' => "User location not found"]);
                }

                if ($user) {
                    // $token = CrmToken::where(['location_id' => $location_id])->first();

                    // Handle base64 image
                    $canvasImageBase64 = $request->canvasImage;
                    if (!$canvasImageBase64) {
                        return response()->json(['status' => false, 'message' => 'No image provided.']);
                    }

                    // Extract the image data (e.g., "data:image/png;base64,...")
                    if (preg_match('/^data:image\/(\w+);base64,/', $canvasImageBase64, $matches)) {
                        $imageType = $matches[1]; // Get image type (e.g., png, jpeg)
                        $canvasImageBase64 = substr($canvasImageBase64, strpos($canvasImageBase64, ',') + 1);
                        $decodedImage = base64_decode($canvasImageBase64);

                        if ($decodedImage === false) {
                            return response()->json(['status' => false, 'message' => 'Invalid image data.']);
                        }

                        // Create a temporary file in memory
                        $tmpFile = tmpfile();
                        $metaData = stream_get_meta_data($tmpFile);
                        $tmpFilePath = $metaData['uri'];
                        file_put_contents($tmpFilePath, $decodedImage);

                        // Prepare payload for further processing
                        $imgNameUnique = 'caseybyzahid' . $location_id . $request->contactId . str_replace(' ', '', now());
                        $imgNameCustom1 = $imgNameUnique . '.' . $imageType;
                        $payload['file'] = new \CURLFile($tmpFilePath, mime_content_type($tmpFilePath), $imgNameCustom1);
                        $payload['name'] = $imgNameUnique;

                        $responseUploadFile = CRM::crmV2Loc($user->id, $location_id, 'medias/upload-file', 'POST', $payload);

                        fclose($tmpFile); // Close and delete the temporary file

                        // sleep(5);
                        if ($responseUploadFile && property_exists($responseUploadFile, 'fileId')) {
                            $contactId = $request->contactId;
                            $userId = $user->id;
                            UploadContactImage::dispatch($imgNameUnique, $location_id, $userId, $contactId)->onQueue(env('JOB_QUEUE_TYPE'));
                            return response()->json(['status' => true, 'message' => 'Image saved']);
                        }

                        return response()->json(['status' => false, 'message' => 'Something went wrong - no data found']);
                    } else {
                        return response()->json(['status' => false, 'message' => 'Invalid base64 image string.']);
                    }
                }
            }
        } catch (\Throwable $th) {
            \Log::info($th->getMessage());
        }
    }

    public function locationWebhookHandle(Request $request)
    {
        try {
            @ini_set('max_execution_time', 120);
            @set_time_limit(120);

            $type = $request->type ?? '';
            $isCreate = $type == 'LocationCreate';

            if ($isCreate || $type == 'LocationUpdate') {
                // $compid = User::where('company_id', $request->companyId)->value('id');
                $compid = User::where('id', 1)->value('id');
                if (!empty($compid)) {
                    if ($isCreate) {
                        // saveLogs("Agency Found", $request->all());
                    }
                } else {
                    return response()->json(['status' => 'failed', 'message' => "Data not saved due to Agency error"]);
                }

                $userEmail = $request->email ?? '';
                if (empty($userEmail) && $isCreate) {

                    $urlmain = 'locations/' . $request->id;
                    $location = CRM::agencyV2($compid, $urlmain);
                    // $location = json_decode($location);
                    if ($location && property_exists($location, 'location')) {
                        $loc = $location->location ?? null;
                        $userEmail = $loc->email ?? "";
                        if (property_exists($loc, 'business')) {
                            $userEmail = $loc->business->email ?? "";
                        }
                    }
                }

                $userDet = User::where(['location' => $request->id])->first();

                if (!$userDet) {
                    $password = $request->password ?? "Getleads2022!";
                    $userDet = new User();
                    $userDet->name =$request->name ?? '';
                    $userDet->location = $request->id;
                    $userDet->role = 0;
                    $userDet->password = Hash::make($password);
                    $userDet->email = $userEmail;
                    $userDet->is_active = 1;
                    $userDet->separate_location = 0;
                    $userDet->save();

                    if($userDet){
                        GetLocationAccessToken::dispatch($userDet->id, $request->id, 'viaAgency')->onQueue(env('JOB_QUEUE_TYPE'));
                    }

                    $credetials = [
                        'reason' => 'Account Created At',
                        'name' => $request->name,
                        'email' => $userEmail,
                        'password' => $password,
                    ];

                } else {

                    $userDet->name =$request->name ?? '';
                    $userDet->email = $userEmail;
                    $userDet->save();

                    $credetials = [
                        'reason' => 'Account Updated! ',
                        'name' => $request->name,
                        'email' => $userEmail,
                        'password' => 'Use Existing Password'
                    ];

                    // $mail =  sendEmail($credetials);
                }

                sendEmail($credetials);
                // $findloc = User::firstOrNew(['location' => $request->id])->fill([
                //     'name' => $request->name ?? '',
                //     'location' => $request->id,
                //     'role' => 0,
                //     'password' =>  Hash::make($password),
                //     'email' => $userEmail,
                //     'is_active' => 1,
                //     'separate_location' => 0,
                // ])->save();

                return response()->json(['status' => 'success', 'message' => "Location Data Saved"]);
            }
        } catch (\Throwable $th) {
            // saveLogs("Webhook Not Processed", $th->getMessage() . ' - ' . $th->getLine());

        }
        return 'Received';
    }



    // public function uploadContactImage(Request $request)
    // {
    //     \Log::info($request->all());
    //     $ssoKey = env('SSO_KEY', null);
    //     if (!$ssoKey) {
    //         return response()->json(['status' => false, 'message' => 'SSO key is not configured.']);
    //     }
    //     $decrypted = self::decryptToken($request->ssoToken, $ssoKey);
    //     if ($decrypted === false) {
    //         return response()->json(['status' => false]);
    //     } else {
    //         $decrypted_data = json_decode($decrypted, true);
    //         $location_id = isset($decrypted_data['activeLocation']) ? $decrypted_data['activeLocation'] : null;
    //         $user = User::where('location', $location_id)->first();
    //         if (!$user) {
    //             return response()->json(['status' => false, 'message' => "User location not found"]);
    //         }

    //         if ($user) {
    //             $token = CrmToken::where(['location_id' => $location_id])->first();
    //             \Log::info($request->file('canvasImage')->isValid());
    //             if (!$request->hasFile('canvasImage') || !$request->file('canvasImage')->isValid()) {
    //                 return response()->json(['status' => false, 'message' => 'No valid image file found.']);
    //             }
    //             $imageFile = $request->file('canvasImage');
    //             $payload['file'] = new \CURLFile($imageFile->getRealPath(), $imageFile->getMimeType(), $imageFile->getClientOriginalName());
    //             $imgNameCustom = 'caseybyzahid' . $location_id . $request->contactId . str_replace(' ', '', now());
    //             // dd($imgNameCustom);
    //             $payload['name'] = $imgNameCustom;
    //             $responseUploadFile = CRM::crmV2Loc($user->id, $location_id, 'medias/upload-file', 'POST', $payload, $token);
    //             // dd( $responseUploadFile);

    //             sleep(5);
    //             if ($responseUploadFile && property_exists($responseUploadFile, 'fileId')) {
    //                 $contactId =  $request->contactId;
    //                 $userId = $user->id;
    //                 UploadContactImage::dispatch($token, $imgNameCustom, $location_id, $userId, $contactId)->onQueue(env('JOB_QUEUE_TYPE'));
    //                 return response()->json(['status' => true, 'message' => 'Image saving is in progress']);
    //             }

    //             return response()->json(['status' => false, 'message' => 'Something went wrong - no data found']);
    //         }
    //     }
    // }
}
