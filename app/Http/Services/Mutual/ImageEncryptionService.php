<?php


namespace App\Http\Services\Mutual;

use App\Repository\EncodedImageRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ImageEncryptionService
{
    public function __construct(private EncodedImageRepositoryInterface $encodedImageRepository){}

    /**
     * embed user's data in image and save image
     * @param mixed $requestAttributeName name of image field in the request
     * @param mixed $email
     * @param mixed $password
     * @throws \Exception
     * @return bool
     */
    public function embed($requestAttributeName, $email, $password){
        $data = [
            'email' => $email,
            'password' => $password,
        ];

        $response = Http::attach(
            'image',
            file_get_contents(request()->file($requestAttributeName)->getRealPath()),
            request()->file($requestAttributeName)->getClientOriginalName(),
        )->post(config('imageEncryptionApi.base_url') . '/embed/', $data);


        if(! $response->successful())
            throw new \Exception("Failed to send request to external api");

        $responseImage = Http::get(config("imageEncryptionApi.base_url") . $response->json()['image_url']);
        if(! $responseImage)
            throw new \Exception("Failed to download Image");


        $content = $responseImage->body();
        $imageName = "encImage_" . time() . ".png";
        $path = "Users/SecPhotos/" . $imageName;
        Storage::disk('public')->put($path,$content);
        $data = [
            'email' => $email,
            'image' => $path,
            'token' => $response->json()['fernet_key']
        ];
        $image = $this->encodedImageRepository->create($data);
        return url("storage/" . $path);
    }

    /**
     * extract the encrypted data from image
     * @param mixed $requestAttributeName name of image field in the request
     * @param mixed $email
     * @throws \Exception
     * @return array email & password
     */
    public function extract($requestAttributeName, $email){

        $key = $this->encodedImageRepository->get('email',$email,['token'])->first();
        // dd($key);
        $response = Http::timeout(120)
        ->attach(
            'image',
            file_get_contents(request()->file($requestAttributeName)->getRealPath()),
            request()->file($requestAttributeName)->getClientOriginalName()
        )->post(config("imageEncryptionApi.base_url") . "/extract/",["fernet_key" => $key->token]);

//        dd($response);
        if(! $response->successful())
            throw new \Exception("Failed to send request to external api");
        return [
            "email" => $response->json()['email'],
            "password" => $response->json()['password'],
        ];

    }

}
