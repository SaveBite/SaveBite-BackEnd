<?php


namespace App\Http\Services\Mutual;

use App\Http\Traits\Responser;
use App\Repository\EncodedImageRepositoryInterface;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ImageEncryptionService
{
    use Responser;

    public function __construct(private readonly EncodedImageRepositoryInterface $encodedImageRepository)
    {
    }


    /**
     * @throws Exception
     */
    public function embed(mixed $requestAttributeName, mixed $email, mixed $password): bool
    {
        $data = [
            'email' => $email,
            'password' => $password,
        ];

        $response = Http::attach(
            'image',
            file_get_contents(request()->file($requestAttributeName)->getRealPath()),
            request()->file($requestAttributeName)->getClientOriginalName(),
        )->post(config('imageEncryptionApi.base_url').'/embed/', $data);


        if (!$response->successful()) {
            throw new Exception("Failed to send request to external api");
        }

        $responseImage = Http::get(config("imageEncryptionApi.base_url").$response->json()['image_url']);
        if (!$responseImage) {
            throw new Exception("Failed to download Image");
        }


        $content = $responseImage->body();
        $imageName = "encImage_".time().".png";
        $path = "Users/SecPhotos/".$imageName;
        Storage::disk('public')->put($path, $content);
        $data = [
            'email' => $email,
            'image' => $path,
            'token' => $response->json()['fernet_key']
        ];
        $this->encodedImageRepository->create($data);
        return url("storage/".$path);
    }

    /**
     * @throws Exception
     */
    public function extract(mixed $requestAttributeName, mixed $email): array
    {
        $key = $this->encodedImageRepository->get('email', $email, ['token'])->first();

        try {
            $response = Http::timeout(20)
                ->attach(
                    'image',
                    file_get_contents(request()->file($requestAttributeName)->getRealPath()),
                    request()->file($requestAttributeName)->getClientOriginalName()
                )
                ->post(config("imageEncryptionApi.base_url")."/extract/", [
                    "fernet_key" => $key->token
                ]);

            if (!$response->successful()) {
                throw new Exception("Failed to send request to external API (status code: ".$response->status().")");
            }

            return [
                "email" => $response->json()['email'],
                "password" => $response->json()['password'],
            ];
        } catch (ConnectionException $e) {
            throw new Exception("The image processing service is currently unavailable. Please try again later.");
        } catch (Exception $e) {
            throw new Exception("Failed to send request to external API. Error: ".$e->getMessage());
        }
    }


}
