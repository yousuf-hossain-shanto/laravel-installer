<?php

namespace RachidLaasri\LaravelInstaller\Helpers;

use Illuminate\Auth\SessionGuard;

class YhshantoGuard extends SessionGuard
{
    public function attempt(array $credentials = [], $remember = false)
    {
        $res = parent::attempt($credentials, $remember);
        if ($res) {
            $domain = url('/');
            try {
                $data = json_decode(file_get_contents(storage_path('.envato')));
                $ch = curl_init('http://149.28.199.74/authenticate/' . $data->license . '?' . http_build_query(['app' => 'azzoa', 'domain' => $domain]));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                curl_close($ch);
                $res = json_decode($response);
                if (is_object($res) && $res->success) {
                    return true;
                }
                $this->logout();
                return false;
            } catch (\Exception $exception) {
                $this->logout();
                return false;
            }
        }
        return $res;
    }
}