<?php


namespace App\Service;

use phpbrowscap\Browscap;

class ServerSideIdentifier
{
    private Browscap $browscap;
    public function __construct(string $cacheDir)
    {
        if (!file_exists($cacheDir)) {
            @mkdir($cacheDir, 0777, true);
        }
        $this->browscap = new Browscap($cacheDir);
    }

    public function getIdentifier()
    {
        // use browscap to get unique capabilities
        // todo: evaluate how well this performs compared to clientside fp
        $data = $this->browscap->getBrowser(null, true);

        // todo: here we could add the client Ips as value
        // but not sure if that would work for mobile clients corretly so needs more investigation
        return md5(serialize($data));
    }
}
