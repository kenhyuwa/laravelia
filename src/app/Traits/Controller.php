<?php 

namespace Ken\Laravelia\App\Traits;

use Illuminate\Support\Facades\File;

trait Controller
{
	public function __construct()
    {
        $this->view = __v();
    }

    protected function setConfig($data)
    {
    	File::put(base_path('/config.json'), $data);
    }

    protected function setEnvironmentJson($key, $value)
    {
        $data = __config(true);
        $data[$key] = $value;
        $this->setConfig(json_encode($data));
    }

    protected function setEnvironmentValue($envKey, $envValue)
    {
        if ($envValue == trim($envValue) && strpos($envValue, ' ') !== false) {
            $envValue = '"' . $envValue . '"';
        }
        $envFile = app()->environmentFilePath();
        if (file_exists($envFile)) {
            file_put_contents($envFile, str_replace(
                $envKey . '=' . env($envKey), $envKey . '=' . $envValue, file_get_contents($envFile)
            ));
        }
    }
}